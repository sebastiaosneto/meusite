<?php
require_once 'config/config.php';
requireLogin();

$db = Database::getInstance()->getConnection();
$user = getCurrentUser();
$pageTitle = 'Meu Perfil';

$error = '';
$success = '';

// Processar atualização
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = sanitize($_POST['nome']);
    $contato = sanitize($_POST['contato']);
    $email = sanitize($_POST['email']);
    
    // Verificar se e-mail já existe em outro usuário (apenas para admin/tecnico; funcionários podem compartilhar e-mail)
    $allowDuplicateEmail = ($user['tipo'] === 'funcionario');
    if (!$allowDuplicateEmail) {
        $stmt = $db->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
        $stmt->execute([$email, $_SESSION['user_id']]);
        if ($stmt->fetch()) {
            $error = 'Este e-mail já está em uso por outro usuário.';
        }
    }
    if (empty($error)) {
        // Verificar se usuário já existe em outro registro
        $usuario = sanitize($_POST['usuario']);
        $stmt = $db->prepare("SELECT id FROM usuarios WHERE usuario = ? AND id != ?");
        $stmt->execute([$usuario, $_SESSION['user_id']]);
        if ($stmt->fetch()) {
            $error = 'Este usuário já está em uso por outro registro.';
        } else {
            if (!empty($_POST['senha'])) {
                $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
                $stmt = $db->prepare("UPDATE usuarios SET nome = ?, contato = ?, email = ?, usuario = ?, senha = ? WHERE id = ?");
                $stmt->execute([$nome, $contato, $email, $usuario, $senha, $_SESSION['user_id']]);
            } else {
                $stmt = $db->prepare("UPDATE usuarios SET nome = ?, contato = ?, email = ?, usuario = ? WHERE id = ?");
                $stmt->execute([$nome, $contato, $email, $usuario, $_SESSION['user_id']]);
            }
            
            // Atualizar sessão
            $_SESSION['user_name'] = $nome;
            
            $success = 'Dados atualizados com sucesso!';
            $user = getCurrentUser(); // Recarregar dados
        }
    }
}

include 'includes/header.php';
?>

<div class="page-header">
    <h1>Meu Perfil</h1>
</div>

<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<div class="card">
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8'); ?>">
        <div class="form-group">
            <label for="nome">Nome *</label>
            <input type="text" id="nome" name="nome" class="form-control" value="<?php echo htmlspecialchars($user['nome']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="contato">Contato</label>
            <input type="text" id="contato" name="contato" class="form-control" value="<?php echo htmlspecialchars($user['contato'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label for="email">E-mail *</label>
            <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="usuario">Usuário *</label>
            <input type="text" id="usuario" name="usuario" class="form-control" value="<?php echo htmlspecialchars($user['usuario']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="senha">Nova Senha</label>
            <input type="password" id="senha" name="senha" class="form-control">
            <small class="text-muted">Deixe em branco para manter a senha atual</small>
        </div>
        
        <div class="form-group">
            <label>Tipo de Usuário</label>
            <input type="text" class="form-control" value="<?php 
                echo $user['tipo'] === 'admin' ? 'Administrador' : 
                    ($user['tipo'] === 'tecnico' ? 'Técnico' : 'Funcionário'); 
            ?>" disabled>
        </div>
        
        <?php if ($user['tipo'] === 'funcionario' && $user['empresa_id']): ?>
            <?php
            $stmt = $db->prepare("SELECT nome FROM empresas WHERE id = ?");
            $stmt->execute([$user['empresa_id']]);
            $empresa = $stmt->fetch();
            ?>
            <div class="form-group">
                <label>Empresa</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($empresa['nome'] ?? '-'); ?>" disabled>
            </div>
        <?php endif; ?>
        
        <div class="actions">
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>

