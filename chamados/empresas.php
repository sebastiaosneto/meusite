<?php
require_once 'config/config.php';
checkUserType(['admin']);

$db = Database::getInstance()->getConnection();
$pageTitle = 'Gerenciar Empresas';

function redirectEmpresasWithResult($success = false) {
    $param = $success ? 'success=1' : 'error=1';
    header('Location: empresas.php?' . $param);
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
            $endereco = sanitize($_POST['endereco'] ?? '');

            if (empty($nome) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Dados inválidos para criação da empresa.');
            }

            $ativo = isset($_POST['ativo']) ? 1 : 0;
            $stmt = $db->prepare("INSERT INTO empresas (nome, contato, email, endereco, ativo) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nome, $contato, $email, $endereco, $ativo]);
        } elseif ($action === 'update') {
            $id = (int) ($_POST['id'] ?? 0);
            $nome = sanitize($_POST['nome'] ?? '');
            $contato = sanitize($_POST['contato'] ?? '');
            $email = sanitize($_POST['email'] ?? '');
            $endereco = sanitize($_POST['endereco'] ?? '');
            $ativo = isset($_POST['ativo']) ? 1 : 0;

            if ($id <= 0 || empty($nome) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Dados inválidos para atualização da empresa.');
            }

            $stmt = $db->prepare("SELECT id FROM empresas WHERE id = ?");
            $stmt->execute([$id]);
            if (!$stmt->fetch()) {
                throw new Exception('Empresa não encontrada para atualização.');
            }

            $stmt = $db->prepare("UPDATE empresas SET nome = ?, contato = ?, email = ?, endereco = ?, ativo = ? WHERE id = ?");
            $stmt->execute([$nome, $contato, $email, $endereco, $ativo, $id]);
        } elseif ($action === 'delete') {
            $id = (int) ($_POST['id'] ?? 0);
            if ($id <= 0) {
                throw new Exception('ID inválido para exclusão.');
            }

            $stmt = $db->prepare("DELETE FROM empresas WHERE id = ?");
            $stmt->execute([$id]);
            if ($stmt->rowCount() < 1) {
                throw new Exception('Empresa não encontrada para exclusão.');
            }
        } else {
            throw new Exception('Ação não suportada.');
        }

        $db->commit();
        redirectEmpresasWithResult(true);
    } catch (Throwable $e) {
        if ($db->inTransaction()) {
            $db->rollBack();
        }

        error_log('Erro no CRUD de empresas: ' . $e->getMessage());
        redirectEmpresasWithResult(false);
    }
}

// Buscar empresas
$stmt = $db->query("SELECT * FROM empresas ORDER BY nome");
$empresas = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="page-header">
    <h1>Empresas</h1>
    <button class="btn btn-primary" onclick="openModal('modalEmpresa')">
        <i class="fas fa-plus"></i> Nova Empresa
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
                <th>Endereço</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($empresas)): ?>
                <tr>
                    <td colspan="6" class="text-center">Nenhuma empresa cadastrada</td>
                </tr>
            <?php else: ?>
                <?php foreach ($empresas as $empresa): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($empresa['nome']); ?></td>
                        <td><?php echo htmlspecialchars($empresa['contato'] ?? '-'); ?></td>
                        <td><?php echo htmlspecialchars($empresa['email']); ?></td>
                        <td><?php echo htmlspecialchars($empresa['endereco'] ?? '-'); ?></td>
                        <td>
                            <span class="badge <?php echo $empresa['ativo'] ? 'badge-success' : 'badge-secondary'; ?>">
                                <?php echo $empresa['ativo'] ? 'Ativa' : 'Inativa'; ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-info" onclick="editEmpresa(<?php echo htmlspecialchars(json_encode($empresa)); ?>)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="deleteEmpresa(<?php echo $empresa['id']; ?>)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal Empresa -->
<div id="modalEmpresa" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Nova Empresa</h2>
            <button class="close" onclick="closeModal('modalEmpresa')">&times;</button>
        </div>
        <form method="POST" id="formEmpresa">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="action" id="formAction" value="create">
            <input type="hidden" name="id" id="empresaId">
            
            <div class="form-group">
                <label for="nome">Nome da Empresa *</label>
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
                <label for="endereco">Endereço</label>
                <textarea id="endereco" name="endereco" class="form-control" rows="3"></textarea>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" id="ativo" name="ativo" checked> Ativa
                </label>
            </div>
            
            <div class="actions">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalEmpresa')">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script>
function editEmpresa(empresa) {
    document.getElementById('modalTitle').textContent = 'Editar Empresa';
    document.getElementById('formAction').value = 'update';
    document.getElementById('empresaId').value = empresa.id;
    document.getElementById('nome').value = empresa.nome;
    document.getElementById('contato').value = empresa.contato || '';
    document.getElementById('email').value = empresa.email;
    document.getElementById('endereco').value = empresa.endereco || '';
    document.getElementById('ativo').checked = empresa.ativo == 1;
    openModal('modalEmpresa');
}

function deleteEmpresa(id) {
    if (confirmDelete('Tem certeza que deseja excluir esta empresa? Todos os funcionários e chamados relacionados serão afetados.')) {
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

