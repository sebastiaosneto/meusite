<?php
// Verificação Rápida do Sistema

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Verificação do Sistema</title>";
echo "<style>body{font-family:Arial;padding:20px;} .ok{color:green;} .erro{color:red;} .aviso{color:orange;}</style>";
echo "</head><body>";
echo "<h1>🔍 Verificação do Sistema de Chamados</h1>";

$erros = [];
$avisos = [];

// 1. Verificar PHP
echo "<h2>1. PHP</h2>";
echo "Versão: <strong>" . phpversion() . "</strong><br>";
if (version_compare(phpversion(), '7.4.0', '<')) {
    $erros[] = "PHP 7.4 ou superior é necessário";
    echo "<span class='erro'>❌ Versão muito antiga</span><br>";
} else {
    echo "<span class='ok'>✅ Versão OK</span><br>";
}

// 2. Verificar extensões
echo "<h2>2. Extensões PHP</h2>";
$extensoes = ['pdo' => 'PDO', 'pdo_mysql' => 'PDO MySQL', 'mbstring' => 'Multibyte String', 'session' => 'Session'];
foreach ($extensoes as $ext => $nome) {
    if (extension_loaded($ext)) {
        echo "<span class='ok'>✅</span> $nome<br>";
    } else {
        $erros[] = "Extensão $nome não está instalada";
        echo "<span class='erro'>❌</span> $nome - <strong>NECESSÁRIA</strong><br>";
    }
}

// 3. Verificar arquivos
echo "<h2>3. Arquivos do Sistema</h2>";
$arquivos = [
    'config/config.php' => 'Configuração principal',
    'config/database.php' => 'Configuração do banco',
    'config/functions.php' => 'Funções auxiliares',
    'includes/header.php' => 'Cabeçalho',
    'includes/footer.php' => 'Rodapé'
];

foreach ($arquivos as $arquivo => $desc) {
    if (file_exists($arquivo)) {
        echo "<span class='ok'>✅</span> $desc<br>";
    } else {
        $erros[] = "Arquivo $arquivo não encontrado";
        echo "<span class='erro'>❌</span> $desc - Arquivo não encontrado<br>";
    }
}

// 4. Testar configuração
echo "<h2>4. Configuração</h2>";
try {
    if (file_exists('config/config.php')) {
        require_once 'config/config.php';
        echo "<span class='ok'>✅</span> Configuração carregada<br>";
        echo "ROOT_PATH: <code>" . ROOT_PATH . "</code><br>";
        echo "BASE_URL: <code>" . BASE_URL . "</code><br>";
    } else {
        throw new Exception("Arquivo config.php não encontrado");
    }
} catch (Exception $e) {
    $erros[] = "Erro ao carregar configuração: " . $e->getMessage();
    echo "<span class='erro'>❌</span> " . $e->getMessage() . "<br>";
}

// 5. Testar banco de dados
echo "<h2>5. Banco de Dados</h2>";
try {
    if (class_exists('Database')) {
        $db = Database::getInstance()->getConnection();
        echo "<span class='ok'>✅</span> Conexão estabelecida<br>";
        
        // Verificar se as tabelas existem
        $tabelas = ['usuarios', 'empresas', 'chamados', 'tipos_atendimento', 'historico_chamados'];
        $tabelasFaltando = [];
        
        foreach ($tabelas as $tabela) {
            $stmt = $db->query("SHOW TABLES LIKE '$tabela'");
            if ($stmt->rowCount() == 0) {
                $tabelasFaltando[] = $tabela;
            }
        }
        
        if (empty($tabelasFaltando)) {
            echo "<span class='ok'>✅</span> Todas as tabelas existem<br>";
        } else {
            $avisos[] = "Tabelas faltando: " . implode(', ', $tabelasFaltando);
            echo "<span class='aviso'>⚠️</span> Tabelas faltando: <strong>" . implode(', ', $tabelasFaltando) . "</strong><br>";
            echo "→ Importe o arquivo <code>database.sql</code> no phpMyAdmin<br>";
        }
    } else {
        throw new Exception("Classe Database não encontrada");
    }
} catch (PDOException $e) {
    $erros[] = "Erro de conexão: " . $e->getMessage();
    echo "<span class='erro'>❌</span> Erro na conexão: <strong>" . $e->getMessage() . "</strong><br>";
    echo "<br><strong>Possíveis causas:</strong><br>";
    echo "1. Banco de dados 'sistema_chamados' não existe<br>";
    echo "2. MySQL não está rodando no XAMPP<br>";
    echo "3. Credenciais incorretas em config/database.php<br>";
    echo "<br><strong>Solução:</strong><br>";
    echo "1. Abra o XAMPP e inicie o MySQL<br>";
    echo "2. Acesse phpMyAdmin: <a href='http://localhost/phpmyadmin' target='_blank'>http://localhost/phpmyadmin</a><br>";
    echo "3. Crie o banco 'sistema_chamados'<br>";
    echo "4. Importe o arquivo database.sql<br>";
} catch (Exception $e) {
    $erros[] = $e->getMessage();
    echo "<span class='erro'>❌</span> " . $e->getMessage() . "<br>";
}

// 6. Permissões
echo "<h2>6. Permissões</h2>";
if (!file_exists('uploads')) {
    if (@mkdir('uploads', 0777, true)) {
        echo "<span class='ok'>✅</span> Pasta uploads criada<br>";
    } else {
        $avisos[] = "Não foi possível criar a pasta uploads";
        echo "<span class='aviso'>⚠️</span> Não foi possível criar a pasta uploads<br>";
    }
} else {
    if (is_writable('uploads')) {
        echo "<span class='ok'>✅</span> Pasta uploads é gravável<br>";
    } else {
        $avisos[] = "Pasta uploads não é gravável";
        echo "<span class='aviso'>⚠️</span> Pasta uploads não é gravável<br>";
    }
}

// Resumo
echo "<hr><h2>📊 Resumo</h2>";

if (empty($erros) && empty($avisos)) {
    echo "<div style='background:#d4edda;padding:15px;border-radius:5px;'>";
    echo "<strong style='color:green;'>✅ Sistema OK! Você pode acessar o login:</strong><br>";
    echo "<a href='login.php' style='display:inline-block;margin-top:10px;padding:10px 20px;background:#0066cc;color:white;text-decoration:none;border-radius:5px;'>Acessar Login</a>";
    echo "</div>";
} else {
    if (!empty($erros)) {
        echo "<div style='background:#f8d7da;padding:15px;border-radius:5px;margin-bottom:10px;'>";
        echo "<strong style='color:red;'>❌ Erros encontrados (" . count($erros) . "):</strong><br>";
        echo "<ul>";
        foreach ($erros as $erro) {
            echo "<li>$erro</li>";
        }
        echo "</ul>";
        echo "</div>";
    }
    
    if (!empty($avisos)) {
        echo "<div style='background:#fff3cd;padding:15px;border-radius:5px;'>";
        echo "<strong style='color:orange;'>⚠️ Avisos (" . count($avisos) . "):</strong><br>";
        echo "<ul>";
        foreach ($avisos as $aviso) {
            echo "<li>$aviso</li>";
        }
        echo "</ul>";
        echo "</div>";
    }
    
    echo "<br><strong>📖 Consulte o arquivo:</strong> <code>SOLUCAO_ERRO_500.md</code> para instruções detalhadas.";
}

echo "</body></html>";
?>

