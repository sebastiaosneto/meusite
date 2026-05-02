<?php
require_once 'config/config.php';
checkUserType(['admin']);

$db = Database::getInstance()->getConnection();
$pageTitle = 'Gerenciar Funcionários';

function redirectFuncionariosWithResult($success = false) {
    $param = $success ? 'success=1' : 'error=1';
    header('Location: funcionarios.php?' . $param);
    exit;
}

// Processar ações
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (empty($_POST['action'])) {
            throw new Exception('Ação inválida.');
        }

        $action = $_POST['action'];
        $db->beginTransaction();

        if ($action === 'create') {
            $nome = sanitize($_POST['nome'] ?? '');
            $contato = sanitize($_POST['contato'] ?? '');
            $email = sanitize($_POST['email'] ?? '');
            $empresa_id = (int) ($_POST['empresa_id'] ?? 0);
            $usuario = sanitize($_POST['usuario'] ?? '');
            $senhaRaw = $_POST['senha'] ?? '';

            if (empty($nome) || empty($email) || empty($usuario) || empty($senhaRaw) || $empresa_id <= 0 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Dados inválidos para criação do funcionário.');
            }

            $stmt = $db->prepare("SELECT id FROM empresas WHERE id = ? AND ativo = 1");
            $stmt->execute([$empresa_id]);
            if (!$stmt->fetch()) {
                throw new Exception('Empresa inválida para o funcionário.');
            }

            $senha = password_hash($senhaRaw, PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO usuarios (nome, contato, email, empresa_id, usuario, senha, tipo) VALUES (?, ?, ?, ?, ?, ?, 'funcionario')");
            $stmt->execute([$nome, $contato, $email, $empresa_id, $usuario, $senha]);
        } elseif ($action === 'update') {
            $id = (int) ($_POST['id'] ?? 0);
            $nome = sanitize($_POST['nome'] ?? '');
            $contato = sanitize($_POST['contato'] ?? '');
            $email = sanitize($_POST['email'] ?? '');
            $empresa_id = (int) ($_POST['empresa_id'] ?? 0);
            $usuario = sanitize($_POST['usuario'] ?? '');
            $ativo = isset($_POST['ativo']) ? 1 : 0;

            if ($id <= 0 || empty($nome) || empty($email) || empty($usuario) || $empresa_id <= 0 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Dados inválidos para atualização do funcionário.');
            }

            $stmt = $db->prepare("SELECT id FROM usuarios WHERE id = ? AND tipo = 'funcionario'");
            $stmt->execute([$id]);
            if (!$stmt->fetch()) {
                throw new Exception('Funcionário não encontrado para atualização.');
            }

            $stmt = $db->prepare("SELECT id FROM empresas WHERE id = ? AND ativo = 1");
            $stmt->execute([$empresa_id]);
            if (!$stmt->fetch()) {
                throw new Exception('Empresa inválida para o funcionário.');
            }

            if (!empty($_POST['senha'])) {
                $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
                $stmt = $db->prepare("UPDATE usuarios SET nome = ?, contato = ?, email = ?, empresa_id = ?, usuario = ?, senha = ?, ativo = ? WHERE id = ? AND tipo = 'funcionario'");
                $stmt->execute([$nome, $contato, $email, $empresa_id, $usuario, $senha, $ativo, $id]);
            } else {
                $stmt = $db->prepare("UPDATE usuarios SET nome = ?, contato = ?, email = ?, empresa_id = ?, usuario = ?, ativo = ? WHERE id = ? AND tipo = 'funcionario'");
                $stmt->execute([$nome, $contato, $email, $empresa_id, $usuario, $ativo, $id]);
            }
        } elseif ($action === 'delete') {
            $id = (int) ($_POST['id'] ?? 0);
            if ($id <= 0) {
                throw new Exception('ID inválido para exclusão.');
            }

            $stmt = $db->prepare("DELETE FROM usuarios WHERE id = ? AND tipo = 'funcionario'");
            $stmt->execute([$id]);
            if ($stmt->rowCount() < 1) {
                throw new Exception('Funcionário não encontrado para exclusão.');
            }
        } else {
            throw new Exception('Ação não suportada.');
        }

        $db->commit();
        redirectFuncionariosWithResult(true);
    } catch (Throwable $e) {
        if ($db->inTransaction()) {
            $db->rollBack();
        }

        error_log('Erro no CRUD de funcionários: ' . $e->getMessage());
        redirectFuncionariosWithResult(false);
    }
}

// Buscar funcionários com empresa
$stmt = $db->query("
    SELECT u.*, e.nome as empresa_nome 
    FROM usuarios u 
    LEFT JOIN empresas e ON u.empresa_id = e.id 
    WHERE u.tipo = 'funcionario' 
    ORDER BY u.nome
");
$funcionarios = $stmt->fetchAll();

// Buscar empresas para o select
$stmt = $db->query("SELECT id, nome FROM empresas WHERE ativo = 1 ORDER BY nome");
$empresas = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="page-header">
    <h1>Funcionários</h1>
    <button class="btn btn-primary" onclick="openModal('modalFuncionario')">
        <i class="fas fa-plus"></i> Novo Funcionário
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
                <th>Nome</th>
                <th>Contato</th>
                <th>E-mail</th>
                <th>Empresa</th>
                <th>Usuário</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($funcionarios)): ?>
                <tr>
                    <td colspan="7" class="text-center">Nenhum funcionário cadastrado</td>
                </tr>
            <?php else: ?>
                <?php foreach ($funcionarios as $funcionario): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($funcionario['nome']); ?></td>
                        <td><?php echo htmlspecialchars($funcionario['contato'] ?? '-'); ?></td>
                        <td><?php echo htmlspecialchars($funcionario['email']); ?></td>
                        <td><?php echo htmlspecialchars($funcionario['empresa_nome'] ?? '-'); ?></td>
                        <td><?php echo htmlspecialchars($funcionario['usuario']); ?></td>
                        <td>
                            <span class="badge <?php echo $funcionario['ativo'] ? 'badge-success' : 'badge-secondary'; ?>">
                                <?php echo $funcionario['ativo'] ? 'Ativo' : 'Inativo'; ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-info" onclick="editFuncionario(<?php echo htmlspecialchars(json_encode($funcionario)); ?>)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="deleteFuncionario(<?php echo $funcionario['id']; ?>)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal Funcionário -->
<div id="modalFuncionario" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Novo Funcionário</h2>
            <button class="close" onclick="closeModal('modalFuncionario')">&times;</button>
        </div>
        <form method="POST" id="formFuncionario">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="action" id="formAction" value="create">
            <input type="hidden" name="id" id="funcionarioId">
            
            <div class="form-group">
                <label for="nome">Nome *</label>
                <input type="text" id="nome" name="nome" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="contato">Contato</label>
                <input type="text" id="contato" name="contato" class="form-control">
            </div>
            
            <div class="form-group">
                <label for="email">E-mail *</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            
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
                <label for="usuario">Usuário *</label>
                <input type="text" id="usuario" name="usuario" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="senha">Senha <span id="senhaLabel">*</span></label>
                <input type="password" id="senha" name="senha" class="form-control">
                <small id="senhaHelp" class="text-muted">Deixe em branco para manter a senha atual</small>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" id="ativo" name="ativo" checked> Ativo
                </label>
            </div>
            
            <div class="actions">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalFuncionario')">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script>
function editFuncionario(funcionario) {
    document.getElementById('modalTitle').textContent = 'Editar Funcionário';
    document.getElementById('formAction').value = 'update';
    document.getElementById('funcionarioId').value = funcionario.id;
    document.getElementById('nome').value = funcionario.nome;
    document.getElementById('contato').value = funcionario.contato || '';
    document.getElementById('email').value = funcionario.email;
    document.getElementById('empresa_id').value = funcionario.empresa_id || '';
    document.getElementById('usuario').value = funcionario.usuario;
    document.getElementById('senha').required = false;
    document.getElementById('senhaLabel').textContent = '';
    document.getElementById('senhaHelp').style.display = 'block';
    document.getElementById('ativo').checked = funcionario.ativo == 1;
    openModal('modalFuncionario');
}

function deleteFuncionario(id) {
    if (confirmDelete('Tem certeza que deseja excluir este funcionário?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            ${window.getCsrfInputHtml()}
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="${id}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<?php include 'includes/footer.php'; ?>

