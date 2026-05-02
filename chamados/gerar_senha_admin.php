<?php
// Script para gerar hash da senha admin123
// Execute este arquivo no navegador para obter o hash correto

$senha = 'admin123';
$hash = password_hash($senha, PASSWORD_BCRYPT);

echo "<h1>Hash da Senha 'admin123'</h1>";
echo "<p><strong>Hash gerado:</strong></p>";
echo "<textarea style='width:100%;height:100px;font-family:monospace;'>$hash</textarea>";

echo "<hr>";
echo "<h2>Comando SQL para inserir/atualizar:</h2>";
echo "<pre style='background:#f5f5f5;padding:15px;border-radius:5px;'>";
echo "-- Inserir ou atualizar usuário admin\n";
echo "INSERT INTO usuarios (nome, email, usuario, senha, tipo, ativo) \n";
echo "VALUES (\n";
echo "    'Administrador', \n";
echo "    'admin@sistema.com', \n";
echo "    'admin', \n";
echo "    '$hash', \n";
echo "    'admin', \n";
echo "    1\n";
echo ")\n";
echo "ON DUPLICATE KEY UPDATE\n";
echo "    nome = 'Administrador',\n";
echo "    email = 'admin@sistema.com',\n";
echo "    senha = '$hash',\n";
echo "    ativo = 1;\n";
echo "</pre>";

echo "<hr>";
echo "<h2>Ou use este comando simples (atualiza se existir):</h2>";
echo "<pre style='background:#f5f5f5;padding:15px;border-radius:5px;'>";
echo "UPDATE usuarios \n";
echo "SET senha = '$hash',\n";
echo "    ativo = 1\n";
echo "WHERE usuario = 'admin';\n";
echo "</pre>";

echo "<hr>";
echo "<h2>Teste de verificação:</h2>";
echo "<p>Para testar se o hash está correto:</p>";
echo "<pre style='background:#f5f5f5;padding:15px;border-radius:5px;'>";
echo "<?php\n";
echo "\$hash = '$hash';\n";
echo "\$senha = 'admin123';\n";
echo "if (password_verify(\$senha, \$hash)) {\n";
echo "    echo 'Senha correta!';\n";
echo "} else {\n";
echo "    echo 'Senha incorreta!';\n";
echo "}\n";
echo "?>";
echo "</pre>";

// Teste automático
if (password_verify($senha, $hash)) {
    echo "<p style='color:green;'><strong>✅ Hash gerado com sucesso e testado!</strong></p>";
} else {
    echo "<p style='color:red;'><strong>❌ Erro ao gerar hash!</strong></p>";
}
?>

