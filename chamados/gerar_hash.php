<?php
/**
 * Script para gerar hash da senha admin123
 * Acesse este arquivo no navegador para obter o hash correto
 */

$senha = 'admin123';
$hash = password_hash($senha, PASSWORD_BCRYPT);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Gerar Hash da Senha</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: 0 auto; }
        .box { background: #f5f5f5; padding: 20px; border-radius: 5px; margin: 20px 0; }
        pre { background: #fff; padding: 15px; border: 1px solid #ddd; border-radius: 3px; overflow-x: auto; }
        .success { color: green; font-weight: bold; }
        .hash { font-family: monospace; font-size: 14px; word-break: break-all; }
    </style>
</head>
<body>
    <h1>🔑 Gerador de Hash para Senha Admin</h1>
    
    <div class="box">
        <h2>Hash Gerado para "admin123":</h2>
        <p class="hash"><?php echo $hash; ?></p>
    </div>

    <div class="box">
        <h2>✅ Comando SQL Completo:</h2>
        <pre>INSERT INTO usuarios (nome, email, usuario, senha, tipo, ativo) 
VALUES (
    'Administrador', 
    'admin@sistema.com', 
    'admin', 
    '<?php echo $hash; ?>', 
    'admin', 
    1
)
ON DUPLICATE KEY UPDATE
    nome = 'Administrador',
    email = 'admin@sistema.com',
    senha = '<?php echo $hash; ?>',
    ativo = 1;</pre>
    </div>

    <div class="box">
        <h2>🔄 Comando SQL para Atualizar (se já existe):</h2>
        <pre>UPDATE usuarios 
SET senha = '<?php echo $hash; ?>',
    ativo = 1,
    nome = 'Administrador',
    email = 'admin@sistema.com'
WHERE usuario = 'admin';</pre>
    </div>

    <div class="box">
        <h2>🧪 Teste de Verificação:</h2>
        <?php
        if (password_verify($senha, $hash)) {
            echo "<p class='success'>✅ Hash gerado com sucesso! A senha 'admin123' está correta.</p>";
        } else {
            echo "<p style='color:red;'>❌ Erro: Hash não corresponde à senha!</p>";
        }
        ?>
    </div>

    <div class="box">
        <h2>📋 Instruções:</h2>
        <ol>
            <li>Copie o comando SQL acima</li>
            <li>Acesse o phpMyAdmin da sua hospedagem</li>
            <li>Selecione o banco de dados <code>sistema_chamados</code></li>
            <li>Vá na aba <strong>SQL</strong></li>
            <li>Cole o comando e clique em <strong>Executar</strong></li>
            <li>Teste o login com: <strong>admin</strong> / <strong>admin123</strong></li>
        </ol>
    </div>

    <hr>
    <p><small>⚠️ Após usar este script, delete-o por segurança!</small></p>
</body>
</html>

