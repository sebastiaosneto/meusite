<?php
require_once 'config/config.php';
requireLogin();

$db = Database::getInstance()->getConnection();
$user = getCurrentUser();
$pageTitle = 'Chamados';

function redirectChamadosWithResult($success = false) {
    $param = $success ? 'success=1' : 'error=1';
    header('Location: chamados.php?' . $param);
    exit;
}

// Processar ações
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emailPayload = null;

    try {
        if (empty($_POST['action'])) {
            throw new Exception('Ação inválida.');
        }

        $action = $_POST['action'];
        $db->beginTransaction();

        if ($action === 'create') {
            $allowedPrioridades = ['baixa', 'media', 'alta', 'urgente'];
            $empresa_id = $_SESSION['user_type'] === 'funcionario' ? (int) $user['empresa_id'] : (int) ($_POST['empresa_id'] ?? 0);
            $funcionario_id = $_SESSION['user_type'] === 'funcionario' ? (int) $_SESSION['user_id'] : (int) ($_POST['funcionario_id'] ?? 0);
            $tipo_atendimento_id = (int) ($_POST['tipo_atendimento_id'] ?? 0);
            $titulo = sanitize($_POST['titulo'] ?? '');
            $descricao = sanitize($_POST['descricao'] ?? '');
            $prioridade = $_POST['prioridade'] ?? '';

            if (empty($titulo) || empty($descricao) || !in_array($prioridade, $allowedPrioridades, true)) {
                throw new Exception('Dados obrigatórios inválidos no chamado.');
            }

            $stmt = $db->prepare("SELECT id, empresa_id, tipo, ativo FROM usuarios WHERE id = ?");
            $stmt->execute([$funcionario_id]);
            $funcionarioRow = $stmt->fetch();
            if (!$funcionarioRow || $funcionarioRow['tipo'] !== 'funcionario' || (int) $funcionarioRow['ativo'] !== 1) {
                throw new Exception('Funcionário inválido para abertura do chamado.');
            }

            if ($_SESSION['user_type'] !== 'funcionario' && (int) $funcionarioRow['empresa_id'] !== $empresa_id) {
                throw new Exception('Funcionário não pertence à empresa selecionada.');
            }

            if ($_SESSION['user_type'] === 'funcionario' && (int) $funcionarioRow['id'] !== (int) $_SESSION['user_id']) {
                throw new Exception('Usuário sem permissão para abrir chamado para outro funcionário.');
            }

            $stmt = $db->prepare("SELECT id FROM tipos_atendimento WHERE id = ? AND ativo = 1");
            $stmt->execute([$tipo_atendimento_id]);
            if (!$stmt->fetch()) {
                throw new Exception('Tipo de atendimento inválido.');
            }

            // Upload de anexo
            $anexo = null;
            if (isset($_FILES['anexo']) && $_FILES['anexo']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = uploadFile($_FILES['anexo'], 'chamado');
                if (is_string($uploadResult)) {
                    $anexo = $uploadResult;
                } else {
                    throw new Exception($uploadResult['error'] ?? 'Erro ao fazer upload do anexo.');
                }
            }

            $stmt = $db->prepare("INSERT INTO chamados (empresa_id, funcionario_id, tipo_atendimento_id, titulo, descricao, prioridade, anexo) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$empresa_id, $funcionario_id, $tipo_atendimento_id, $titulo, $descricao, $prioridade, $anexo]);
            $chamado_id = (int) $db->lastInsertId();

            // Registrar no histórico
            $stmt = $db->prepare("INSERT INTO historico_chamados (chamado_id, usuario_id, acao, descricao) VALUES (?, ?, 'abertura', ?)");
            $stmt->execute([$chamado_id, $_SESSION['user_id'], "Chamado aberto: $titulo"]);

            // Dados para envio de e-mail após commit
            $stmt = $db->prepare("SELECT * FROM chamados WHERE id = ?");
            $stmt->execute([$chamado_id]);
            $chamado = $stmt->fetch();

            $stmt = $db->prepare("SELECT * FROM usuarios WHERE id = ?");
            $stmt->execute([$funcionario_id]);
            $funcionario = $stmt->fetch();

            $stmt = $db->prepare("SELECT * FROM empresas WHERE id = ?");
            $stmt->execute([$empresa_id]);
            $empresa = $stmt->fetch();

            $stmt = $db->query("SELECT * FROM usuarios WHERE tipo = 'tecnico' AND ativo = 1 LIMIT 1");
            $tecnico = $stmt->fetch();

            $emailPayload = [
                'type' => 'aberto',
                'chamado' => $chamado,
                'funcionario' => $funcionario,
                'empresa' => $empresa,
                'tecnico' => $tecnico
            ];
        } elseif ($action === 'atender') {
            if ($_SESSION['user_type'] !== 'tecnico') {
                throw new Exception('Apenas técnicos podem atender chamados.');
            }

            $id = (int) ($_POST['id'] ?? 0);
            if ($id <= 0) {
                throw new Exception('Chamado inválido para atendimento.');
            }

            $stmt = $db->prepare("
                UPDATE chamados
                SET status = 'em_atendimento', tecnico_id = ?
                WHERE id = ? AND status IN ('pendente', 'reaberto')
            ");
            $stmt->execute([$_SESSION['user_id'], $id]);
            if ($stmt->rowCount() < 1) {
                throw new Exception('Chamado não está disponível para atendimento.');
            }

            // Registrar no histórico
            $stmt = $db->prepare("INSERT INTO historico_chamados (chamado_id, usuario_id, acao, descricao) VALUES (?, ?, 'atendimento', 'Chamado em atendimento')");
            $stmt->execute([$id, $_SESSION['user_id']]);

            // Dados para envio de e-mail após commit
            $stmt = $db->prepare("
                SELECT c.*, u.email as funcionario_email, u.nome as funcionario_nome, e.nome as empresa_nome
                FROM chamados c
                JOIN usuarios u ON c.funcionario_id = u.id
                LEFT JOIN empresas e ON c.empresa_id = e.id
                WHERE c.id = ?
            ");
            $stmt->execute([$id]);
            $chamado = $stmt->fetch();
            if (!$chamado) {
                throw new Exception('Chamado não encontrado após atendimento.');
            }

            $stmt = $db->prepare("SELECT * FROM usuarios WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $tecnico = $stmt->fetch();

            $funcionario = [
                'email' => $chamado['funcionario_email'],
                'nome' => $chamado['funcionario_nome']
            ];
            $empresa = [
                'nome' => $chamado['empresa_nome']
            ];

            $emailPayload = [
                'type' => 'atendido',
                'chamado' => $chamado,
                'funcionario' => $funcionario,
                'empresa' => $empresa,
                'tecnico' => $tecnico
            ];
        } elseif ($action === 'finalizar') {
            if ($_SESSION['user_type'] !== 'tecnico') {
                throw new Exception('Apenas técnicos podem finalizar chamados.');
            }

            $id = (int) ($_POST['id'] ?? 0);
            $solucao = sanitize($_POST['solucao'] ?? '');
            if ($id <= 0 || empty($solucao)) {
                throw new Exception('Dados inválidos para finalização do chamado.');
            }

            $stmt = $db->prepare("
                UPDATE chamados
                SET status = 'finalizado', solucao = ?, finalizado_at = NOW()
                WHERE id = ? AND tecnico_id = ? AND status = 'em_atendimento'
            ");
            $stmt->execute([$solucao, $id, $_SESSION['user_id']]);
            if ($stmt->rowCount() < 1) {
                throw new Exception('Chamado não pode ser finalizado por este técnico.');
            }

            // Registrar no histórico
            $stmt = $db->prepare("INSERT INTO historico_chamados (chamado_id, usuario_id, acao, descricao) VALUES (?, ?, 'finalizacao', ?)");
            $stmt->execute([$id, $_SESSION['user_id'], "Chamado finalizado. Solução: $solucao"]);

            // Dados para envio de e-mail após commit
            $stmt = $db->prepare("
                SELECT c.*, u.email as funcionario_email, u.nome as funcionario_nome, e.nome as empresa_nome
                FROM chamados c
                JOIN usuarios u ON c.funcionario_id = u.id
                LEFT JOIN empresas e ON c.empresa_id = e.id
                WHERE c.id = ?
            ");
            $stmt->execute([$id]);
            $chamado = $stmt->fetch();
            if (!$chamado) {
                throw new Exception('Chamado não encontrado após finalização.');
            }

            $stmt = $db->prepare("SELECT * FROM usuarios WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $tecnico = $stmt->fetch();

            $funcionario = [
                'email' => $chamado['funcionario_email'],
                'nome' => $chamado['funcionario_nome']
            ];
            $empresa = [
                'nome' => $chamado['empresa_nome']
            ];

            $emailPayload = [
                'type' => 'finalizado',
                'chamado' => $chamado,
                'funcionario' => $funcionario,
                'empresa' => $empresa,
                'tecnico' => $tecnico
            ];
        } elseif ($action === 'reabrir') {
            if ($_SESSION['user_type'] !== 'funcionario') {
                throw new Exception('Apenas funcionários podem reabrir chamados.');
            }

            $id = (int) ($_POST['id'] ?? 0);
            $descricao = sanitize($_POST['descricao'] ?? '');
            if ($id <= 0 || empty($descricao)) {
                throw new Exception('Dados inválidos para reabertura do chamado.');
            }

            $stmt = $db->prepare("
                UPDATE chamados
                SET status = 'reaberto'
                WHERE id = ? AND funcionario_id = ? AND status = 'finalizado'
            ");
            $stmt->execute([$id, $_SESSION['user_id']]);
            if ($stmt->rowCount() < 1) {
                throw new Exception('Chamado não pode ser reaberto por este usuário.');
            }

            // Registrar no histórico
            $stmt = $db->prepare("INSERT INTO historico_chamados (chamado_id, usuario_id, acao, descricao) VALUES (?, ?, 'reabertura', ?)");
            $stmt->execute([$id, $_SESSION['user_id'], $descricao]);
        } else {
            throw new Exception('Ação não suportada.');
        }

        $db->commit();

        // Enviar notificação por e-mail fora da transação
        if ($emailPayload && file_exists('config/email.php')) {
            require_once 'config/email.php';
            $emailService = new EmailService();
            if ($emailPayload['type'] === 'aberto') {
                $emailService->enviarNotificacaoChamadoAberto($emailPayload['chamado'], $emailPayload['funcionario'], $emailPayload['empresa'], $emailPayload['tecnico']);
            } elseif ($emailPayload['type'] === 'atendido') {
                $emailService->enviarNotificacaoChamadoAtendido($emailPayload['chamado'], $emailPayload['funcionario'], $emailPayload['tecnico'], $emailPayload['empresa']);
            } elseif ($emailPayload['type'] === 'finalizado') {
                $emailService->enviarNotificacaoChamadoFinalizado($emailPayload['chamado'], $emailPayload['funcionario'], $emailPayload['tecnico']);
            }
        }

        redirectChamadosWithResult(true);
    } catch (Throwable $e) {
        if ($db->inTransaction()) {
            $db->rollBack();
        }

        error_log('Erro no fluxo de chamados: ' . $e->getMessage());
        redirectChamadosWithResult(false);
    }
}

// Buscar chamados conforme o tipo de usuário
if ($_SESSION['user_type'] === 'admin') {
    $stmt = $db->query("
        SELECT c.*, 
               e.nome as empresa_nome,
               u.nome as funcionario_nome,
               u.email as funcionario_email,
               t.nome as tecnico_nome,
               ta.nome as tipo_nome
        FROM chamados c
        LEFT JOIN empresas e ON c.empresa_id = e.id
        LEFT JOIN usuarios u ON c.funcionario_id = u.id
        LEFT JOIN usuarios t ON c.tecnico_id = t.id
        LEFT JOIN tipos_atendimento ta ON c.tipo_atendimento_id = ta.id
        ORDER BY c.created_at DESC
    ");
    $chamados = $stmt->fetchAll();
} elseif ($_SESSION['user_type'] === 'tecnico') {
    $stmt = $db->prepare("
        SELECT c.*, 
               e.nome as empresa_nome,
               u.nome as funcionario_nome,
               u.email as funcionario_email,
               t.nome as tecnico_nome,
               ta.nome as tipo_nome
        FROM chamados c
        LEFT JOIN empresas e ON c.empresa_id = e.id
        LEFT JOIN usuarios u ON c.funcionario_id = u.id
        LEFT JOIN usuarios t ON c.tecnico_id = t.id
        LEFT JOIN tipos_atendimento ta ON c.tipo_atendimento_id = ta.id
        WHERE c.tecnico_id = ? OR c.status = 'pendente'
        ORDER BY c.created_at DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $chamados = $stmt->fetchAll();
} else {
    // Funcionário vê seus chamados e os da empresa
    $stmt = $db->prepare("
        SELECT c.*, 
               e.nome as empresa_nome,
               u.nome as funcionario_nome,
               u.email as funcionario_email,
               t.nome as tecnico_nome,
               ta.nome as tipo_nome
        FROM chamados c
        LEFT JOIN empresas e ON c.empresa_id = e.id
        LEFT JOIN usuarios u ON c.funcionario_id = u.id
        LEFT JOIN usuarios t ON c.tecnico_id = t.id
        LEFT JOIN tipos_atendimento ta ON c.tipo_atendimento_id = ta.id
        WHERE c.empresa_id = ?
        ORDER BY c.created_at DESC
    ");
    $stmt->execute([$_SESSION['user_empresa_id']]);
    $chamados = $stmt->fetchAll();
}

// Buscar dados para formulários
$stmt = $db->query("SELECT id, nome FROM empresas WHERE ativo = 1 ORDER BY nome");
$empresas = $stmt->fetchAll();

$stmt = $db->query("SELECT id, nome FROM tipos_atendimento WHERE ativo = 1 ORDER BY nome");
$tipos_atendimento = $stmt->fetchAll();

if ($_SESSION['user_type'] === 'admin') {
    $stmt = $db->query("SELECT id, nome FROM usuarios WHERE tipo = 'funcionario' AND ativo = 1 ORDER BY nome");
    $funcionarios = $stmt->fetchAll();
} else {
    $funcionarios = [];
}

include 'includes/header.php';
?>

<div class="page-header">
    <h1>Chamados</h1>
    <button class="btn btn-primary" onclick="openModal('modalChamado')">
        <i class="fas fa-plus"></i> Novo Chamado
    </button>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">Operação realizada com sucesso!</div>
<?php endif; ?>
<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger">Não foi possível concluir a operação. Verifique os dados e tente novamente.</div>
<?php endif; ?>

<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <?php if ($_SESSION['user_type'] !== 'funcionario'): ?>
                <th>Empresa</th>
                <?php endif; ?>
                <?php if ($_SESSION['user_type'] === 'admin'): ?>
                <th>Funcionário</th>
                <?php endif; ?>
                <th>Tipo</th>
                <th>Prioridade</th>
                <th>Status</th>
                <th>Técnico</th>
                <th>Data</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($chamados)): ?>
                <tr>
                    <td colspan="10" class="text-center">Nenhum chamado encontrado</td>
                </tr>
            <?php else: ?>
                <?php foreach ($chamados as $chamado): ?>
                    <tr>
                        <td>#<?php echo $chamado['id']; ?></td>
                        <td><?php echo htmlspecialchars($chamado['titulo']); ?></td>
                        <?php if ($_SESSION['user_type'] !== 'funcionario'): ?>
                        <td><?php echo htmlspecialchars($chamado['empresa_nome'] ?? '-'); ?></td>
                        <?php endif; ?>
                        <?php if ($_SESSION['user_type'] === 'admin'): ?>
                        <td><?php echo htmlspecialchars($chamado['funcionario_nome'] ?? '-'); ?></td>
                        <?php endif; ?>
                        <td><?php echo htmlspecialchars($chamado['tipo_nome'] ?? '-'); ?></td>
                        <td>
                            <span class="badge <?php echo getPrioridadeClass($chamado['prioridade']); ?>">
                                <?php echo formatPrioridade($chamado['prioridade']); ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge <?php echo getStatusClass($chamado['status']); ?>">
                                <?php echo formatStatus($chamado['status']); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($chamado['tecnico_nome'] ?? '-'); ?></td>
                        <td><?php echo formatDate($chamado['created_at']); ?></td>
                        <td>
                            <a href="historico.php?id=<?php echo $chamado['id']; ?>" class="btn btn-sm btn-info">
                                <i class="fas fa-history"></i> Histórico
                            </a>
                            <button class="btn btn-sm btn-info" onclick="viewChamado(<?php echo htmlspecialchars(json_encode($chamado)); ?>)">
                                <i class="fas fa-eye"></i>
                            </button>
                            <?php if ($_SESSION['user_type'] === 'tecnico' && $chamado['status'] === 'pendente'): ?>
                                <button class="btn btn-sm btn-primary" onclick="atenderChamado(<?php echo $chamado['id']; ?>)">
                                    <i class="fas fa-hand-paper"></i> Atender
                                </button>
                            <?php endif; ?>
                            <?php if ($_SESSION['user_type'] === 'tecnico' && $chamado['status'] === 'em_atendimento' && $chamado['tecnico_id'] == $_SESSION['user_id']): ?>
                                <button class="btn btn-sm btn-success" onclick="finalizarChamado(<?php echo $chamado['id']; ?>)">
                                    <i class="fas fa-check"></i> Finalizar
                                </button>
                            <?php endif; ?>
                            <?php if ($_SESSION['user_type'] === 'funcionario' && $chamado['status'] === 'finalizado' && $chamado['funcionario_id'] == $_SESSION['user_id']): ?>
                                <button class="btn btn-sm btn-warning" onclick="reabrirChamado(<?php echo $chamado['id']; ?>)">
                                    <i class="fas fa-redo"></i> Reabrir
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal Chamado -->
<div id="modalChamado" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Novo Chamado</h2>
            <button class="close" onclick="closeModal('modalChamado')">&times;</button>
        </div>
        <form method="POST" enctype="multipart/form-data" id="formChamado">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="action" value="create">
            
            <?php if ($_SESSION['user_type'] === 'admin' || $_SESSION['user_type'] === 'tecnico'): ?>
            <div class="form-group">
                <label for="empresa_id">Empresa *</label>
                <select id="empresa_id" name="empresa_id" class="form-control" required>
                    <option value="">Selecione uma empresa</option>
                    <?php foreach ($empresas as $empresa): ?>
                        <option value="<?php echo $empresa['id']; ?>"><?php echo htmlspecialchars($empresa['nome']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="funcionario_id">Funcionário *</label>
                <select id="funcionario_id" name="funcionario_id" class="form-control" required>
                    <option value="">Selecione um funcionário</option>
                </select>
            </div>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="tipo_atendimento_id">Tipo de Atendimento *</label>
                <select id="tipo_atendimento_id" name="tipo_atendimento_id" class="form-control" required>
                    <option value="">Selecione um tipo</option>
                    <?php foreach ($tipos_atendimento as $tipo): ?>
                        <option value="<?php echo $tipo['id']; ?>"><?php echo htmlspecialchars($tipo['nome']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="titulo">Título *</label>
                <input type="text" id="titulo" name="titulo" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="descricao">Descrição *</label>
                <textarea id="descricao" name="descricao" class="form-control" rows="5" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="prioridade">Prioridade *</label>
                <select id="prioridade" name="prioridade" class="form-control" required>
                    <option value="baixa">Baixa</option>
                    <option value="media" selected>Média</option>
                    <option value="alta">Alta</option>
                    <option value="urgente">Urgente</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="anexo">Anexo</label>
                <input type="file" id="anexo" name="anexo" class="form-control" accept="image/*,.pdf,.doc,.docx">
                <small class="text-muted">Formatos aceitos: Imagens, PDF, Word (máx. 5MB)</small>
            </div>
            
            <div class="actions">
                <button type="submit" class="btn btn-primary">Abrir Chamado</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalChamado')">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Visualizar Chamado -->
<div id="modalViewChamado" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Detalhes do Chamado</h2>
            <button class="close" onclick="closeModal('modalViewChamado')">&times;</button>
        </div>
        <div id="chamadoDetails"></div>
    </div>
</div>

<!-- Modal Finalizar Chamado -->
<div id="modalFinalizar" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Finalizar Chamado</h2>
            <button class="close" onclick="closeModal('modalFinalizar')">&times;</button>
        </div>
        <form method="POST" id="formFinalizar">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="action" value="finalizar">
            <input type="hidden" name="id" id="finalizarId">
            
            <div class="form-group">
                <label for="solucao">Solução/Descrição do Atendimento *</label>
                <textarea id="solucao" name="solucao" class="form-control" rows="5" required></textarea>
            </div>
            
            <div class="actions">
                <button type="submit" class="btn btn-success">Finalizar</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalFinalizar')">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Reabrir Chamado -->
<div id="modalReabrir" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Reabrir Chamado</h2>
            <button class="close" onclick="closeModal('modalReabrir')">&times;</button>
        </div>
        <form method="POST" id="formReabrir">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="action" value="reabrir">
            <input type="hidden" name="id" id="reabrirId">
            
            <div class="form-group">
                <label for="descricao_reabrir">Motivo da Reabertura *</label>
                <textarea id="descricao_reabrir" name="descricao" class="form-control" rows="5" required></textarea>
            </div>
            
            <div class="actions">
                <button type="submit" class="btn btn-warning">Reabrir</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalReabrir')">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script>
// Carregar funcionários quando empresa for selecionada
<?php if ($_SESSION['user_type'] === 'admin'): ?>
document.getElementById('empresa_id')?.addEventListener('change', function() {
    const empresaId = this.value;
    const funcionarioSelect = document.getElementById('funcionario_id');
    
    if (empresaId) {
        fetch(`api/get_funcionarios.php?empresa_id=${empresaId}`)
            .then(response => response.json())
            .then(data => {
                funcionarioSelect.innerHTML = '<option value="">Selecione um funcionário</option>';
                data.forEach(func => {
                    funcionarioSelect.innerHTML += `<option value="${func.id}">${func.nome}</option>`;
                });
            });
    } else {
        funcionarioSelect.innerHTML = '<option value="">Selecione um funcionário</option>';
    }
});
<?php endif; ?>

function viewChamado(chamado) {
    const details = document.getElementById('chamadoDetails');
    let html = `
        <div class="card">
            <h3>#${chamado.id} - ${chamado.titulo}</h3>
            <p><strong>Status:</strong> <span class="badge ${getStatusClass(chamado.status)}">${formatStatus(chamado.status)}</span></p>
            <p><strong>Prioridade:</strong> <span class="badge ${getPrioridadeClass(chamado.prioridade)}">${formatPrioridade(chamado.prioridade)}</span></p>
            <p><strong>Descrição:</strong> ${chamado.descricao}</p>
            ${chamado.solucao ? `<p><strong>Solução:</strong> ${chamado.solucao}</p>` : ''}
            ${chamado.anexo ? `<p><strong>Anexo:</strong> <a href="uploads/${chamado.anexo}" target="_blank">Ver anexo</a></p>` : ''}
            <p><strong>Data de Abertura:</strong> ${formatDate(chamado.created_at)}</p>
            ${chamado.finalizado_at ? `<p><strong>Data de Finalização:</strong> ${formatDate(chamado.finalizado_at)}</p>` : ''}
        </div>
    `;
    details.innerHTML = html;
    openModal('modalViewChamado');
}

function atenderChamado(id) {
    if (confirm('Deseja atender este chamado?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            ${window.getCsrfInputHtml()}
            <input type="hidden" name="action" value="atender">
            <input type="hidden" name="id" value="${id}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

function finalizarChamado(id) {
    document.getElementById('finalizarId').value = id;
    openModal('modalFinalizar');
}

function reabrirChamado(id) {
    document.getElementById('reabrirId').value = id;
    openModal('modalReabrir');
}

function getStatusClass(status) {
    const classes = {
        'pendente': 'badge-warning',
        'em_atendimento': 'badge-info',
        'finalizado': 'badge-success',
        'reaberto': 'badge-danger',
        'cancelado': 'badge-secondary'
    };
    return classes[status] || 'badge-secondary';
}

function formatStatus(status) {
    const labels = {
        'pendente': 'Pendente',
        'em_atendimento': 'Em Atendimento',
        'finalizado': 'Finalizado',
        'reaberto': 'Reaberto',
        'cancelado': 'Cancelado'
    };
    return labels[status] || status;
}

function getPrioridadeClass(prioridade) {
    const classes = {
        'baixa': 'badge-info',
        'media': 'badge-primary',
        'alta': 'badge-warning',
        'urgente': 'badge-danger'
    };
    return classes[prioridade] || 'badge-secondary';
}

function formatPrioridade(prioridade) {
    const labels = {
        'baixa': 'Baixa',
        'media': 'Média',
        'alta': 'Alta',
        'urgente': 'Urgente'
    };
    return labels[prioridade] || prioridade;
}
</script>

<?php include 'includes/footer.php'; ?>

