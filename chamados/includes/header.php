<?php
if (!isset($pageTitle)) {
    $pageTitle = 'Sistema de Chamados T.I.';
}
$isLoginPage = isset($isLoginPage) && $isLoginPage;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8'); ?>">
    <title><?php echo $pageTitle; ?></title>
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        window.CSRF_TOKEN = "<?php echo htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8'); ?>";
        window.getCsrfInputHtml = function() {
            return '<input type="hidden" name="csrf_token" value="' + window.CSRF_TOKEN + '">';
        };

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('form[method="POST"], form[method="post"]').forEach(function(form) {
                if (!form.querySelector('input[name="csrf_token"]')) {
                    form.insertAdjacentHTML('afterbegin', window.getCsrfInputHtml());
                }
            });
        });
    </script>
</head>
<body<?php echo $isLoginPage ? ' class="login-page"' : ''; ?>>
    <?php if (isLoggedIn()): ?>
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-brand">
                <img src="images/LOGO.png" alt="Logo" class="nav-logo">
                <span>Sistema de Chamados</span>
            </div>
            <ul class="nav-menu">
                <li><a href="index.php"><i class="fas fa-home"></i> Dashboard</a></li>
                
                <?php if ($_SESSION['user_type'] === 'admin'): ?>
                    <li><a href="tecnicos.php"><i class="fas fa-user-cog"></i> Técnicos</a></li>
                    <li><a href="tipos_atendimento.php"><i class="fas fa-list"></i> Tipos de Atendimento</a></li>
                    <li><a href="empresas.php"><i class="fas fa-building"></i> Empresas</a></li>
                    <li><a href="funcionarios.php"><i class="fas fa-users"></i> Funcionários</a></li>
                    <li><a href="chamados.php"><i class="fas fa-ticket-alt"></i> Chamados</a></li>
                    <li><a href="relatorios.php"><i class="fas fa-chart-bar"></i> Relatórios</a></li>
                <?php elseif ($_SESSION['user_type'] === 'tecnico'): ?>
                    <li><a href="chamados.php"><i class="fas fa-ticket-alt"></i> Chamados</a></li>
                    <li><a href="relatorios.php"><i class="fas fa-chart-bar"></i> Relatórios</a></li>
                <?php else: ?>
                    <li><a href="chamados.php"><i class="fas fa-ticket-alt"></i> Meus Chamados</a></li>
                <?php endif; ?>
                
                <li><a href="perfil.php"><i class="fas fa-user"></i> Perfil</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a></li>
            </ul>
            <div class="nav-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>
    <?php endif; ?>
    
    <main class="main-content">

