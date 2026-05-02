<?php
/**
 * Script de Debug para Problemas de Login
 * Acesse este arquivo no navegador para diagnosticar problemas de autenticação
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/config.php';

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Debug Login</title>";
echo "<style>body{font-family:Arial;padding:20px;max-width:900px;margin:0 auto;}";
echo ".ok{color:green;font-weight:bold;} .erro{color:red;font-weight:bold;} .info{background:#e7f3ff;padding:15px;border-left:4px solid #2196F3;margin:10px 0;}";
echo "pre{background:#f5f5f5;padding:10px;border-radius:3px;overflow-x:auto;}";
echo "table{border-collapse:collapse;width:100%;margin:10px 0;} th,td{border:1px solid #ddd;padding:8px;text-align:left;} th{background:#f0f0f0;}</style>";
echo "</head><body>";

echo "<h1>🔍 Debug do Sistema de Login</h1>";

$usuario_teste = 'admin';
$senha_teste = 'admin123';

echo "<div class='info'>";
echo "<strong>Testando login com:</strong><br>";
echo "Usuário: <code>$usuario_teste</code><br>";
echo "Senha: <code>$senha_teste</code><br>";
echo "</div>";

try {
    $db = Database::getInstance()->getConnection();
    
    // 1. Verificar se o usuário existe
    echo "<h2>1. Verificando se o usuário existe no banco</h2>";
    $stmt = $db->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario_teste]);
    $user = $stmt->fetch();
    
    if (!$user) {
        echo "<p class='erro'>❌ Usuário 'admin' NÃO encontrado no banco de dados!</p>";
        echo "<p><strong>Solução:</strong> Execute o comando SQL para inserir o usuário admin.</p>";
        echo "</body></html>";
        exit;
    }
    
    echo "<p class='ok'>✅ Usuário encontrado!</p>";
    echo "<table>";
    echo "<tr><th>Campo</th><th>Valor</th></tr>";
    foreach ($user as $campo => $valor) {
        if ($campo === 'senha') {
            $tamanho = strlen($valor);
            $inicio = substr($valor, 0, 20) . '...';
            echo "<tr><td><strong>$campo</strong></td><td>$inicio <small>(tamanho: $tamanho caracteres)</small></td></tr>";
        } else {
            echo "<tr><td><strong>$campo</strong></td><td>" . htmlspecialchars($valor ?? 'NULL') . "</td></tr>";
        }
    }
    echo "</table>";
    
    // 2. Verificar se está ativo
    echo "<h2>2. Verificando status do usuário</h2>";
    if ($user['ativo'] == 1) {
        echo "<p class='ok'>✅ Usuário está ATIVO</p>";
    } else {
        echo "<p class='erro'>❌ Usuário está INATIVO (ativo = 0)</p>";
        echo "<p><strong>Solução:</strong> Execute: <code>UPDATE usuarios SET ativo = 1 WHERE usuario = 'admin';</code></p>";
    }
    
    // 3. Verificar tamanho do hash
    echo "<h2>3. Verificando hash da senha</h2>";
    $hash_tamanho = strlen($user['senha']);
    echo "<p>Tamanho do hash: <strong>$hash_tamanho caracteres</strong></p>";
    
    if ($hash_tamanho < 60) {
        echo "<p class='erro'>❌ Hash muito curto! Deve ter 60 caracteres.</p>";
        echo "<p>O hash pode ter sido truncado ao inserir no banco.</p>";
        echo "<p><strong>Solução:</strong> Verifique se a coluna 'senha' é VARCHAR(255) ou maior.</p>";
    } else {
        echo "<p class='ok'>✅ Tamanho do hash está correto (60 caracteres)</p>";
    }
    
    // 4. Verificar formato do hash
    echo "<h2>4. Verificando formato do hash</h2>";
    if (strpos($user['senha'], '$2y$') === 0) {
        echo "<p class='ok'>✅ Hash está no formato bcrypt correto (começa com \$2y\$)</p>";
    } else {
        echo "<p class='erro'>❌ Hash não está no formato bcrypt!</p>";
        echo "<p>Hash encontrado: <code>" . substr($user['senha'], 0, 20) . "...</code></p>";
        echo "<p><strong>Solução:</strong> O hash precisa ser gerado com password_hash() do PHP.</p>";
    }
    
    // 5. Testar verificação de senha
    echo "<h2>5. Testando verificação de senha</h2>";
    
    // Mostrar hash completo (primeiros e últimos caracteres)
    $hash_completo = $user['senha'];
    $hash_inicio = substr($hash_completo, 0, 30);
    $hash_fim = substr($hash_completo, -10);
    echo "<p>Hash no banco: <code>$hash_inicio...$hash_fim</code></p>";
    
    // Testar password_verify
    if (password_verify($senha_teste, $hash_completo)) {
        echo "<p class='ok'>✅ Senha verificada com SUCESSO! O login deve funcionar.</p>";
    } else {
        echo "<p class='erro'>❌ Senha NÃO confere! O hash não corresponde à senha 'admin123'.</p>";
        
        echo "<h3>Possíveis causas:</h3>";
        echo "<ul>";
        echo "<li>O hash foi gerado com uma senha diferente</li>";
        echo "<li>O hash foi truncado ao inserir no banco</li>";
        echo "<li>Há espaços ou caracteres extras no hash</li>";
        echo "</ul>";
        
        echo "<h3>Solução:</h3>";
        echo "<p>Execute este comando SQL para gerar um novo hash:</p>";
        echo "<pre>";
        echo "-- Primeiro, gere um novo hash executando gerar_hash.php\n";
        echo "-- Depois execute:\n\n";
        echo "UPDATE usuarios \n";
        echo "SET senha = '[HASH_GERADO_PELO_gerar_hash.php]'\n";
        echo "WHERE usuario = 'admin';";
        echo "</pre>";
    }
    
    // 6. Verificar consulta do login.php
    echo "<h2>6. Simulando consulta do login.php</h2>";
    $stmt = $db->prepare("SELECT * FROM usuarios WHERE usuario = ? AND ativo = 1");
    $stmt->execute([$usuario_teste]);
    $user_login = $stmt->fetch();
    
    if ($user_login) {
        echo "<p class='ok'>✅ Consulta do login.php retorna o usuário</p>";
        
        if (password_verify($senha_teste, $user_login['senha'])) {
            echo "<p class='ok'>✅ password_verify() retorna TRUE - Login deve funcionar!</p>";
        } else {
            echo "<p class='erro'>❌ password_verify() retorna FALSE - Problema no hash!</p>";
        }
    } else {
        echo "<p class='erro'>❌ Consulta do login.php NÃO retorna o usuário</p>";
        echo "<p>Isso pode acontecer se o usuário estiver inativo (ativo = 0)</p>";
    }
    
    // 7. Verificar estrutura da tabela
    echo "<h2>7. Verificando estrutura da tabela</h2>";
    $stmt = $db->query("DESCRIBE usuarios");
    $colunas = $stmt->fetchAll();
    
    echo "<table>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Chave</th></tr>";
    foreach ($colunas as $coluna) {
        $destaque = ($coluna['Field'] === 'senha' && strpos($coluna['Type'], '255') === false) ? "style='background:#ffebee;'" : '';
        echo "<tr $destaque>";
        echo "<td><strong>" . $coluna['Field'] . "</strong></td>";
        echo "<td>" . $coluna['Type'] . "</td>";
        echo "<td>" . $coluna['Null'] . "</td>";
        echo "<td>" . $coluna['Key'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    if (strpos($colunas[array_search('senha', array_column($colunas, 'Field'))]['Type'], '255') === false) {
        echo "<p class='erro'>⚠️ A coluna 'senha' pode ser muito pequena. Recomendado: VARCHAR(255)</p>";
    }
    
} catch (Exception $e) {
    echo "<p class='erro'>❌ Erro: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h2>📋 Resumo e Próximos Passos</h2>";
echo "<div class='info'>";
echo "<p><strong>Se a senha não está funcionando:</strong></p>";
echo "<ol>";
echo "<li>Acesse: <code>gerar_hash.php</code> para gerar um novo hash</li>";
echo "<li>Copie o comando SQL exibido</li>";
echo "<li>Execute no phpMyAdmin</li>";
echo "<li>Teste o login novamente</li>";
echo "</ol>";
echo "</div>";

echo "<p><a href='login.php'>← Voltar para o Login</a></p>";
echo "</body></html>";
?>

