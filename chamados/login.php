<?php
require_once 'config/config.php';

// Se já estiver logado, redirecionar
if (isLoggedIn()) {
    header('Location: ' . BASE_URL . 'index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = sanitize($_POST['usuario'] ?? '');
    $senha = $_POST['senha'] ?? '';
    
    if (empty($usuario) || empty($senha)) {
        $error = 'Preencha todos os campos';
    } else {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE usuario = ? AND ativo = 1");
        $stmt->execute([$usuario]);
        $user = $stmt->fetch();
        
        if ($user) {
            // Verificar se a senha está correta
            if (password_verify($senha, $user['senha'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_type'] = $user['tipo'];
                $_SESSION['user_name'] = $user['nome'];
                $_SESSION['user_empresa_id'] = $user['empresa_id'] ?? null;
                
                header('Location: ' . BASE_URL . 'index.php');
                exit;
            } else {
                $error = 'Usuário ou senha incorretos';
            }
        } else {
            $error = 'Usuário ou senha incorretos';
        }
    }
}

$isLoginPage = true;
include 'includes/header.php';
?>

<div class="login-container">
    <div class="login-bg-pattern"></div>
    <div class="login-box">
        <div class="login-header">
            <img src="images/LOGO.png" alt="Logo" class="login-logo">
            <h1 class="login-title">Sistema de Chamados T.I.</h1>
            <p class="login-subtitle">Acesse sua conta para continuar</p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-danger login-alert"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="" class="login-form">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8'); ?>">
            <div class="form-group">
                <label for="usuario"><i class="fas fa-user"></i> Usuário</label>
                <input type="text" id="usuario" name="usuario" class="form-control login-input" placeholder="Digite seu usuário" required autofocus autocomplete="username">
            </div>
            
            <div class="form-group">
                <label for="senha"><i class="fas fa-lock"></i> Senha</label>
                <input type="password" id="senha" name="senha" class="form-control login-input" placeholder="Digite sua senha" required autocomplete="current-password">
            </div>
            
            <button type="submit" class="btn btn-primary btn-block login-btn">
                <i class="fas fa-sign-in-alt"></i> Entrar
            </button>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

