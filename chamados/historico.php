<?php
require_once 'config/config.php';
requireLogin();

$db = Database::getInstance()->getConnection();
$pageTitle = 'Histórico de Chamados';

function redirectHistoricoWithResult($chamadoId, $success = false) {
    $param = $success ? 'success=1' : 'error=1';
    header('Location: historico.php?id=' . (int) $chamadoId . '&' . $param);
    exit;
}

$chamado_id = $_GET['id'] ?? null;

if (!$chamado_id) {
    header('Location: chamados.php');
    exit;
}

// Verificar permissão para ver o chamado
$stmt = $db->prepare("
    SELECT c.*, 
           e.nome as empresa_nome,
           u.nome as funcionario_nome
    FROM chamados c
    LEFT JOIN empresas e ON c.empresa_id = e.id
    LEFT JOIN usuarios u ON c.funcionario_id = u.id
    WHERE c.id = ?
");

$stmt->execute([$chamado_id]);
$chamado = $stmt->fetch();

if (!$chamado) {
    header('Location: chamados.php');
    exit;
}

// Verificar se o usuário tem permissão
if ($_SESSION['user_type'] === 'funcionario') {
    if ($chamado['funcionario_id'] != $_SESSION['user_id'] && $chamado['empresa_id'] != $_SESSION['user_empresa_id']) {
        header('Location: chamados.php');
        exit;
    }
} elseif ($_SESSION['user_type'] === 'tecnico') {
    if ($chamado['tecnico_id'] != $_SESSION['user_id'] && $chamado['status'] != 'pendente') {
        // Técnico só vê seus chamados ou pendentes
    }
}

// Verificar se coluna de anexo existe no histórico (compatibilidade)
$historicoTemAnexo = false;
try {
    $stmt = $db->query("SHOW COLUMNS FROM historico_chamados LIKE 'anexo'");
    $historicoTemAnexo = (bool) $stmt->fetch();
} catch (Throwable $e) {
    $historicoTemAnexo = false;
}

// Nova interação no histórico (comentário + anexo)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'interacao') {
    try {
        $descricao = sanitize($_POST['descricao'] ?? '');
        if (empty($descricao)) {
            throw new Exception('Descrição da interação é obrigatória.');
        }

        $anexo = null;
        if (isset($_FILES['anexo']) && $_FILES['anexo']['error'] === UPLOAD_ERR_OK) {
            if (!$historicoTemAnexo) {
                throw new Exception('Banco desatualizado para anexos no histórico. Execute a migração.');
            }
            $uploadResult = uploadFile($_FILES['anexo'], 'hist');
            if (is_string($uploadResult)) {
                $anexo = $uploadResult;
            } else {
                throw new Exception($uploadResult['error'] ?? 'Erro ao anexar arquivo na interação.');
            }
        }

        $db->beginTransaction();
        if ($historicoTemAnexo) {
            $stmt = $db->prepare("
                INSERT INTO historico_chamados (chamado_id, usuario_id, acao, descricao, anexo)
                VALUES (?, ?, 'interacao', ?, ?)
            ");
            $stmt->execute([$chamado_id, $_SESSION['user_id'], $descricao, $anexo]);
        } else {
            $stmt = $db->prepare("
                INSERT INTO historico_chamados (chamado_id, usuario_id, acao, descricao)
                VALUES (?, ?, 'interacao', ?)
            ");
            $stmt->execute([$chamado_id, $_SESSION['user_id'], $descricao]);
        }
        $db->commit();

        redirectHistoricoWithResult($chamado_id, true);
    } catch (Throwable $e) {
        if ($db->inTransaction()) {
            $db->rollBack();
        }
        error_log('Erro ao registrar interação no histórico: ' . $e->getMessage());
        redirectHistoricoWithResult($chamado_id, false);
    }
}

// Buscar histórico
$stmt = $db->prepare("
    SELECT h.*, u.nome as usuario_nome, u.tipo as usuario_tipo
    FROM historico_chamados h
    LEFT JOIN usuarios u ON h.usuario_id = u.id
    WHERE h.chamado_id = ?
    ORDER BY h.created_at ASC
");
$stmt->execute([$chamado_id]);
$historico = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="page-header">
    <h1>Histórico do Chamado #<?php echo $chamado['id']; ?></h1>
    <a href="chamados.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Voltar
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h2><?php echo htmlspecialchars($chamado['titulo']); ?></h2>
    </div>
    
    <div class="form-group">
        <strong>Status:</strong>
        <span class="badge <?php echo getStatusClass($chamado['status']); ?>">
            <?php echo formatStatus($chamado['status']); ?>
        </span>
    </div>
    
    <div class="form-group">
        <strong>Prioridade:</strong>
        <span class="badge <?php echo getPrioridadeClass($chamado['prioridade']); ?>">
            <?php echo formatPrioridade($chamado['prioridade']); ?>
        </span>
    </div>
    
    <div class="form-group">
        <strong>Descrição:</strong>
        <p><?php echo nl2br(htmlspecialchars($chamado['descricao'])); ?></p>
    </div>
    
    <?php if ($chamado['solucao']): ?>
    <div class="form-group">
        <strong>Solução:</strong>
        <p><?php echo nl2br(htmlspecialchars($chamado['solucao'])); ?></p>
    </div>
    <?php endif; ?>
    
    <?php if ($chamado['anexo']): ?>
    <div class="form-group">
        <strong>Anexo:</strong>
        <a href="<?php echo UPLOAD_URL . $chamado['anexo']; ?>" target="_blank" class="btn btn-sm btn-info">
            <i class="fas fa-download"></i> Baixar Anexo
        </a>
    </div>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-header">
        <h2>Histórico de Interações</h2>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Interação registrada com sucesso!</div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">Não foi possível registrar a interação.</div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="card" style="margin-bottom: 20px;">
        <input type="hidden" name="action" value="interacao">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8'); ?>">
        <div class="form-group">
            <label for="descricao_interacao"><strong>Adicionar interação</strong></label>
            <textarea id="descricao_interacao" name="descricao" class="form-control" rows="4" required></textarea>
        </div>
        <div class="form-group">
            <label for="anexo_interacao">Anexo (opcional)</label>
            <input type="file" id="anexo_interacao" name="anexo" class="form-control" accept="image/*,.pdf,.doc,.docx">
        </div>
        <div class="actions">
            <button type="submit" class="btn btn-primary">Salvar interação</button>
        </div>
    </form>
    
    <?php if (empty($historico)): ?>
        <p class="text-center">Nenhum histórico registrado.</p>
    <?php else: ?>
        <div class="timeline">
            <?php foreach ($historico as $item): ?>
                <div class="timeline-item">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <div class="timeline-header">
                            <strong><?php echo htmlspecialchars($item['usuario_nome'] ?? 'Sistema'); ?></strong>
                            <span class="badge badge-info">
                                <?php 
                                $acoes = [
                                    'abertura' => 'Abertura',
                                    'atendimento' => 'Em Atendimento',
                                    'finalizacao' => 'Finalização',
                                    'reabertura' => 'Reabertura',
                                    'interacao' => 'Interação'
                                ];
                                echo $acoes[$item['acao']] ?? ucfirst($item['acao']);
                                ?>
                            </span>
                            <small class="text-muted"><?php echo formatDate($item['created_at']); ?></small>
                        </div>
                        <?php if ($item['descricao']): ?>
                            <p><?php echo nl2br(htmlspecialchars($item['descricao'])); ?></p>
                        <?php endif; ?>
                        <?php if ($historicoTemAnexo && !empty($item['anexo'])): ?>
                            <p>
                                <a href="<?php echo UPLOAD_URL . $item['anexo']; ?>" target="_blank" class="btn btn-sm btn-info">
                                    <i class="fas fa-paperclip"></i> Ver anexo
                                </a>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: -25px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background-color: var(--primary-color);
    border: 2px solid var(--white);
    box-shadow: 0 0 0 2px var(--primary-color);
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -19px;
    top: 17px;
    width: 2px;
    height: calc(100% + 13px);
    background-color: var(--border-color);
}

.timeline-content {
    background: var(--light-color);
    padding: 15px;
    border-radius: 5px;
    border-left: 3px solid var(--primary-color);
}

.timeline-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
    flex-wrap: wrap;
}

.timeline-header strong {
    color: var(--primary-color);
}
</style>

<?php include 'includes/footer.php'; ?>

