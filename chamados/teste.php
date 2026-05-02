<?php
// Arquivo de Teste - Diagnóstico do Sistema

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Teste de Diagnóstico do Sistema</h1>";

// Teste 1: PHP
echo "<h2>1. Versão do PHP</h2>";
echo "Versão: " . phpversion() . "<br>";
echo "Status: ✅ OK<br><br>";

// Teste 2: Extensões necessárias
echo "<h2>2. Extensões PHP</h2>";
$extensoes = ['pdo', 'pdo_mysql', 'mbstring', 'session'];
foreach ($extensoes as $ext) {
    $status = extension_loaded($ext) ? '✅' : '❌';
    echo "$ext: $status<br>";
}
echo "<br>";

// Teste 3: Caminhos
echo "<h2>3. Caminhos e Arquivos</h2>";
$arquivos = [
    'config/config.php',
    'config/database.php',
    'config/functions.php',
    'includes/header.php',
    'includes/footer.php'
];

foreach ($arquivos as $arquivo) {
    $existe = file_exists($arquivo) ? '✅' : '❌';
    echo "$arquivo: $existe<br>";
}
echo "<br>";

// Teste 4: Configuração
echo "<h2>4. Carregar Configuração</h2>";
try {
    require_once 'config/config.php';
    echo "✅ Configuração carregada<br>";
    echo "ROOT_PATH: " . ROOT_PATH . "<br>";
    echo "BASE_URL: " . BASE_URL . "<br>";
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "<br>";
}
echo "<br>";

// Teste 5: Banco de Dados
echo "<h2>5. Conexão com Banco de Dados</h2>";
try {
    $db = Database::getInstance()->getConnection();
    echo "✅ Conexão estabelecida<br>";
    
    // Testar query
    $stmt = $db->query("SELECT 1");
    echo "✅ Query de teste executada<br>";
} catch (PDOException $e) {
    echo "❌ Erro na conexão: " . $e->getMessage() . "<br>";
    echo "<strong>Possíveis causas:</strong><br>";
    echo "- Banco de dados 'sistema_chamados' não existe<br>";
    echo "- Usuário/senha do MySQL incorretos<br>";
    echo "- MySQL não está rodando<br>";
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "<br>";
}
echo "<br>";

// Teste 6: Sessão
echo "<h2>6. Sessão PHP</h2>";
try {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    echo "✅ Sessão iniciada<br>";
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "<br>";
}
echo "<br>";

// Teste 7: Permissões
echo "<h2>7. Permissões de Pastas</h2>";
$pastas = ['uploads', 'images'];
foreach ($pastas as $pasta) {
    if (file_exists($pasta)) {
        $writable = is_writable($pasta) ? '✅' : '❌';
        echo "$pasta: $writable (gravável)<br>";
    } else {
        echo "$pasta: ⚠️ Não existe (será criada automaticamente)<br>";
    }
}
echo "<br>";

echo "<hr>";
echo "<h2>Próximos Passos</h2>";
echo "<ol>";
echo "<li>Se houver erros de banco de dados, importe o arquivo <code>database.sql</code> no phpMyAdmin</li>";
echo "<li>Verifique as configurações em <code>config/database.php</code></li>";
echo "<li>Certifique-se de que o MySQL está rodando no XAMPP</li>";
echo "</ol>";

echo "<p><a href='login.php'>Tentar acessar o login</a></p>";
?>

