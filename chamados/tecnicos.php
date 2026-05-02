<?php
require_once 'config/config.php';
checkUserType(['admin']);

$db = Database::getInstance()->getConnection();
$pageTitle = 'Gerenciar Técnicos';

function redirectTecnicosWithResult($success = false) {
    $param = $success ? 'success=1' : 'error=1';
    header('Location: tecnicos.php?' . $param);
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
            $usuario = sanitize($_POST['usuario'] ?? '');
            $senhaRaw = $_POST['senha'] ?? '';

            if (empty($nome) || empty($email) || empty($usuario) || empty($senhaRaw) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Dados inválidos para criação do técnico.');
            }

            $senha = password_hash($senhaRaw, PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO usuarios (nome, contato, email, usuario, senha, tipo) VALUES (?, ?, ?, ?, ?, 'tecnico')");
            $stmt->execute([$nome, $contato, $email, $usuario, $senha]);
        } elseif ($action === 'update') {
            $id = (int) ($_POST['id'] ?? 0);
            $nome = sanitize($_POST['nome'] ?? '');
            $contato = sanitize($_POST['contato'] ?? '');
            $email = sanitize($_POST['email'] ?? '');
            $usuario = sanitize($_POST['usuario'] ?? '');
            $ativo = isset($_POST['ativo']) ? 1 : 0;

            if ($id <= 0 || empty($nome) || empty($email) || empty($usuario) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Dados inválidos para atualização do técnico.');
            }

            $stmt = $db->prepare("SELECT id FROM usuarios WHERE id = ? AND tipo = 'tecnico'");
            $stmt->execute([$id]);
            if (!$stmt->fetch()) {
                throw new Exception('Técnico não encontrado para atualização.');
            }

            if (!empty($_POST['senha'])) {
                $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
                $stmt = $db->prepare("UPDATE usuarios SET nome = ?, contato = ?, email = ?, usuario = ?, senha = ?, ativo = ? WHERE id = ? AND tipo = 'tecnico'");
                $stmt->execute([$nome, $contato, $email, $usuario, $senha, $ativo, $id]);
            } else {
                $stmt = $db->prepare("UPDATE usuarios SET nome = ?, contato = ?, email = ?, usuario = ?, ativo = ? WHERE id = ? AND tipo = 'tecnico'");
                $stmt->execute([$nome, $contato, $email, $usuario, $ativo, $id]);
            }
        } elseif ($action === 'delete') {
            $id = (int) ($_POST['id'] ?? 0);
            if ($id <= 0) {
                throw new Exception('ID inválido para exclusão.');
            }

            $stmt = $db->prepare("DELETE FROM usuarios WHERE id = ? AND tipo = 'tecnico'");
            $stmt->execute([$id]);
            if ($stmt->rowCount() < 1) {
                throw new Exception('Técnico não encontrado para exclusão.');
            }
        } else {
            throw new Exception('Ação não suportada.');
        }

        $db->commit();
        redirectTecnicosWithResult(true);
    } catch (Throwable $e) {
        if ($db->inTransaction()) {
            $db->rollBack();
        }

        error_log('Erro no CRUD de técnicos: ' . $e->getMessage());
        redirectTecnicosWithResult(false);
    }
}

// Buscar técnicos
$stmt = $db->query("SELECT * FROM usuarios WHERE tipo = 'tecnico' ORDER BY nome");
$tecnicos = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="page-header">
    <h1>Técnicos</h1>
    <button class="btn btn-primary" onclick="openModal('modalTecnico')">
        <i class="fas fa-plus"></i> Novo Técnico
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
                <th>Usuário</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($tecnicos)): ?>
                <tr>
                    <td colspan="6" class="text-center">Nenhum técnico cadastrado</td>
                </tr>
            <?php else: ?>
                <?php foreach ($tecnicos as $tecnico): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($tecnico['nome']); ?></td>
                        <td><?php echo htmlspecialchars($tecnico['contato'] ?? '-'); ?></td>
                        <td><?php echo htmlspecialchars($tecnico['email']); ?></td>
                        <td><?php echo htmlspecialchars($tecnico['usuario']); ?></td>
                        <td>
                            <span class="badge <?php echo $tecnico['ativo'] ? 'badge-success' : 'badge-secondary'; ?>">
                                <?php echo $tecnico['ativo'] ? 'Ativo' : 'Inativo'; ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-info" onclick="editTecnico(<?php echo htmlspecialchars(json_encode($tecnico)); ?>)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="deleteTecnico(<?php echo $tecnico['id']; ?>)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal Técnico -->
<div id="modalTecnico" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Novo Técnico</h2>
            <button class="close" onclick="closeModal('modalTecnico')">&times;</button>
        </div>
        <form method="POST" id="formTecnico">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="action" id="formAction" value="create">
            <input type="hidden" name="id" id="tecnicoId">
            
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
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalTecnico')">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script>
function editTecnico(tecnico) {
    document.getElementById('modalTitle').textContent = 'Editar Técnico';
    document.getElementById('formAction').value = 'update';
    document.getElementById('tecnicoId').value = tecnico.id;
    document.getElementById('nome').value = tecnico.nome;
    document.getElementById('contato').value = tecnico.contato || '';
    document.getElementById('email').value = tecnico.email;
    document.getElementById('usuario').value = tecnico.usuario;
    document.getElementById('senha').required = false;
    document.getElementById('senhaLabel').textContent = '';
    document.getElementById('senhaHelp').style.display = 'block';
    document.getElementById('ativo').checked = tecnico.ativo == 1;
    openModal('modalTecnico');
}

function deleteTecnico(id) {
    if (confirmDelete('Tem certeza que deseja excluir este técnico?')) {
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

// Resetar formulário ao fechar
document.getElementById('modalTecnico').addEventListener('click', function(e) {
    if (e.target === this) {
        resetForm();
    }
});

function resetForm() {
    document.getElementById('formTecnico').reset();
    document.getElementById('modalTitle').textContent = 'Novo Técnico';
    document.getElementById('formAction').value = 'create';
    document.getElementById('tecnicoId').value = '';
    document.getElementById('senha').required = true;
    document.getElementById('senhaLabel').textContent = '*';
    document.getElementById('senhaHelp').style.display = 'none';
    document.getElementById('ativo').checked = true;
}
</script>

<?php include 'includes/footer.php'; ?>

