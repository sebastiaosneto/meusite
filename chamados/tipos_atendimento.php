<?php
require_once 'config/config.php';
checkUserType(['admin']);

$db = Database::getInstance()->getConnection();
$pageTitle = 'Tipos de Atendimento';

function redirectTiposWithResult($success = false) {
    $param = $success ? 'success=1' : 'error=1';
    header('Location: tipos_atendimento.php?' . $param);
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
            $descricao = sanitize($_POST['descricao'] ?? '');

            if (empty($nome)) {
                throw new Exception('Nome é obrigatório para criação do tipo.');
            }

            $stmt = $db->prepare("INSERT INTO tipos_atendimento (nome, descricao) VALUES (?, ?)");
            $stmt->execute([$nome, $descricao]);
        } elseif ($action === 'update') {
            $id = (int) ($_POST['id'] ?? 0);
            $nome = sanitize($_POST['nome'] ?? '');
            $descricao = sanitize($_POST['descricao'] ?? '');
            $ativo = isset($_POST['ativo']) ? 1 : 0;

            if ($id <= 0 || empty($nome)) {
                throw new Exception('Dados inválidos para atualização do tipo.');
            }

            $stmt = $db->prepare("SELECT id FROM tipos_atendimento WHERE id = ?");
            $stmt->execute([$id]);
            if (!$stmt->fetch()) {
                throw new Exception('Tipo não encontrado para atualização.');
            }

            $stmt = $db->prepare("UPDATE tipos_atendimento SET nome = ?, descricao = ?, ativo = ? WHERE id = ?");
            $stmt->execute([$nome, $descricao, $ativo, $id]);
        } elseif ($action === 'delete') {
            $id = (int) ($_POST['id'] ?? 0);
            if ($id <= 0) {
                throw new Exception('ID inválido para exclusão.');
            }

            $stmt = $db->prepare("DELETE FROM tipos_atendimento WHERE id = ?");
            $stmt->execute([$id]);
            if ($stmt->rowCount() < 1) {
                throw new Exception('Tipo não encontrado para exclusão.');
            }
        } else {
            throw new Exception('Ação não suportada.');
        }

        $db->commit();
        redirectTiposWithResult(true);
    } catch (Throwable $e) {
        if ($db->inTransaction()) {
            $db->rollBack();
        }

        error_log('Erro no CRUD de tipos de atendimento: ' . $e->getMessage());
        redirectTiposWithResult(false);
    }
}

// Buscar tipos
$stmt = $db->query("SELECT * FROM tipos_atendimento ORDER BY nome");
$tipos = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="page-header">
    <h1>Tipos de Atendimento</h1>
    <button class="btn btn-primary" onclick="openModal('modalTipo')">
        <i class="fas fa-plus"></i> Novo Tipo
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
                <th>Descrição</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($tipos)): ?>
                <tr>
                    <td colspan="4" class="text-center">Nenhum tipo cadastrado</td>
                </tr>
            <?php else: ?>
                <?php foreach ($tipos as $tipo): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($tipo['nome']); ?></td>
                        <td><?php echo htmlspecialchars($tipo['descricao'] ?? '-'); ?></td>
                        <td>
                            <span class="badge <?php echo $tipo['ativo'] ? 'badge-success' : 'badge-secondary'; ?>">
                                <?php echo $tipo['ativo'] ? 'Ativo' : 'Inativo'; ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-info" onclick="editTipo(<?php echo htmlspecialchars(json_encode($tipo)); ?>)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="deleteTipo(<?php echo $tipo['id']; ?>)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal Tipo -->
<div id="modalTipo" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Novo Tipo de Atendimento</h2>
            <button class="close" onclick="closeModal('modalTipo')">&times;</button>
        </div>
        <form method="POST" id="formTipo">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="action" id="formAction" value="create">
            <input type="hidden" name="id" id="tipoId">
            
            <div class="form-group">
                <label for="nome">Nome *</label>
                <input type="text" id="nome" name="nome" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="descricao">Descrição</label>
                <textarea id="descricao" name="descricao" class="form-control" rows="3"></textarea>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" id="ativo" name="ativo" checked> Ativo
                </label>
            </div>
            
            <div class="actions">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalTipo')">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script>
function editTipo(tipo) {
    document.getElementById('modalTitle').textContent = 'Editar Tipo de Atendimento';
    document.getElementById('formAction').value = 'update';
    document.getElementById('tipoId').value = tipo.id;
    document.getElementById('nome').value = tipo.nome;
    document.getElementById('descricao').value = tipo.descricao || '';
    document.getElementById('ativo').checked = tipo.ativo == 1;
    openModal('modalTipo');
}

function deleteTipo(id) {
    if (confirmDelete('Tem certeza que deseja excluir este tipo de atendimento?')) {
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

