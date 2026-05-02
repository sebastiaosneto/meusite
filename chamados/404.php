<?php
require_once 'config/config.php';
$pageTitle = 'Página não encontrada';
include 'includes/header.php';
?>

<div class="empty-state">
    <i class="fas fa-exclamation-triangle"></i>
    <h2>404 - Página não encontrada</h2>
    <p>A página que você está procurando não existe.</p>
    <a href="index.php" class="btn btn-primary">Voltar ao Dashboard</a>
</div>

<?php include 'includes/footer.php'; ?>

