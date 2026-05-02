<?php
/**
 * Script para Testar se o PHPMailer está Instalado Corretamente
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/config.php';

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Teste PHPMailer</title>";
echo "<style>body{font-family:Arial;padding:20px;max-width:800px;margin:0 auto;}";
echo ".ok{color:green;font-weight:bold;} .erro{color:red;font-weight:bold;} .aviso{color:orange;font-weight:bold;}";
echo ".box{background:#f5f5f5;padding:20px;border-radius:5px;margin:20px 0;}";
echo "pre{background:#fff;padding:15px;border:1px solid #ddd;border-radius:3px;overflow-x:auto;}</style>";
echo "</head><body>";

echo "<h1>📧 Teste de Instalação do PHPMailer</h1>";

// 1. Verificar se vendor/autoload.php existe
echo "<div class='box'>";
echo "<h2>1. Verificando carregamento do PHPMailer</h2>";
$autoload_path = ROOT_PATH . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
echo "<p><strong>Composer autoload:</strong> <code>$autoload_path</code></p>";
$local_phpmailer_path = ROOT_PATH . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'PHPMailer' . DIRECTORY_SEPARATOR . 'src';
echo "<p><strong>PHPMailer local:</strong> <code>$local_phpmailer_path</code></p>";

if (file_exists($autoload_path)) {
    echo "<p class='ok'>✅ Arquivo autoload.php encontrado (Composer)!</p>";
    require_once $autoload_path;
} elseif (
    file_exists($local_phpmailer_path . DIRECTORY_SEPARATOR . 'PHPMailer.php') &&
    file_exists($local_phpmailer_path . DIRECTORY_SEPARATOR . 'SMTP.php') &&
    file_exists($local_phpmailer_path . DIRECTORY_SEPARATOR . 'Exception.php')
) {
    echo "<p class='ok'>✅ PHPMailer local encontrado em <code>libraries/PHPMailer/src</code>!</p>";
    require_once $local_phpmailer_path . DIRECTORY_SEPARATOR . 'Exception.php';
    require_once $local_phpmailer_path . DIRECTORY_SEPARATOR . 'SMTP.php';
    require_once $local_phpmailer_path . DIRECTORY_SEPARATOR . 'PHPMailer.php';
} else {
    echo "<p class='erro'>❌ Nem Composer autoload nem PHPMailer local foram encontrados!</p>";
    echo "<p><strong>Possíveis causas:</strong></p>";
    echo "<ul>";
    echo "<li>Composer não foi executado: <code>composer install</code></li>";
    echo "<li>Pasta <code>libraries/PHPMailer/src</code> ausente</li>";
    echo "<li>Permissões de pasta</li>";
    echo "</ul>";
    echo "</body></html>";
    exit;
}
echo "</div>";

// 2. Verificar se a classe PHPMailer existe
echo "<div class='box'>";
echo "<h2>2. Verificando classe PHPMailer</h2>";

if (class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
    echo "<p class='ok'>✅ Classe PHPMailer encontrada!</p>";
    echo "<p><strong>Namespace:</strong> <code>PHPMailer\\PHPMailer\\PHPMailer</code></p>";
} else {
    echo "<p class='erro'>❌ Classe PHPMailer NÃO encontrada!</p>";
    echo "<p><strong>Verificando namespaces disponíveis...</strong></p>";
    
    // Tentar outros namespaces possíveis
    $namespaces = [
        'PHPMailer\\PHPMailer\\PHPMailer',
        'PHPMailer\\PHPMailer',
        'PHPMailer',
        'phpmailer\\phpmailer\\phpmailer'
    ];
    
    foreach ($namespaces as $ns) {
        if (class_exists($ns)) {
            echo "<p class='aviso'>⚠️ Classe encontrada com namespace diferente: <code>$ns</code></p>";
        }
    }
}
echo "</div>";

// 3. Verificar estrutura da pasta vendor
echo "<div class='box'>";
echo "<h2>3. Verificando fonte da biblioteca</h2>";
$vendor_path = ROOT_PATH . DIRECTORY_SEPARATOR . 'vendor';
$local_phpmailer_path = ROOT_PATH . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'PHPMailer' . DIRECTORY_SEPARATOR . 'src';

if (is_dir($vendor_path)) {
    echo "<p class='ok'>✅ Pasta vendor existe</p>";
    
    // Listar pastas dentro de vendor
    $pastas = scandir($vendor_path);
    $pastas = array_filter($pastas, function($item) use ($vendor_path) {
        return is_dir($vendor_path . DIRECTORY_SEPARATOR . $item) && !in_array($item, ['.', '..']);
    });
    
    echo "<p><strong>Pastas encontradas em vendor:</strong></p>";
    echo "<ul>";
    foreach ($pastas as $pasta) {
        $caminho_completo = $vendor_path . DIRECTORY_SEPARATOR . $pasta;
        echo "<li><code>$pasta</code>";
        
        // Verificar se é phpmailer
        if (stripos($pasta, 'phpmail') !== false) {
            echo " <span class='ok'>✅ (PHPMailer encontrado)</span>";
            
            // Listar conteúdo
            if (is_dir($caminho_completo)) {
                $subpastas = scandir($caminho_completo);
                $subpastas = array_filter($subpastas, function($item) use ($caminho_completo) {
                    return is_dir($caminho_completo . DIRECTORY_SEPARATOR . $item) && !in_array($item, ['.', '..']);
                });
                
                if (!empty($subpastas)) {
                    echo "<ul>";
                    foreach ($subpastas as $subpasta) {
                        echo "<li><code>$subpasta</code></li>";
                    }
                    echo "</ul>";
                }
            }
        }
        echo "</li>";
    }
    echo "</ul>";
    
    // Verificar caminho correto
    $phpmailer_path_correto = $vendor_path . DIRECTORY_SEPARATOR . 'phpmailer' . DIRECTORY_SEPARATOR . 'phpmailer';
    $phpmailer_path_alternativo = $vendor_path . DIRECTORY_SEPARATOR . 'phpmail' . DIRECTORY_SEPARATOR . 'phpmail';
    
    if (is_dir($phpmailer_path_correto)) {
        echo "<p class='ok'>✅ Pasta correta encontrada: <code>vendor/phpmailer/phpmailer</code></p>";
    } elseif (is_dir($phpmailer_path_alternativo)) {
        echo "<p class='aviso'>⚠️ Pasta encontrada com nome diferente: <code>vendor/phpmail/phpmail</code></p>";
        echo "<p>Isso pode indicar um problema na instalação do Composer.</p>";
        echo "<p><strong>Solução:</strong> Execute novamente: <code>composer install</code> ou <code>composer update</code></p>";
    } else {
        echo "<p class='erro'>❌ Pasta do PHPMailer não encontrada!</p>";
    }
} else {
    if (
        file_exists($local_phpmailer_path . DIRECTORY_SEPARATOR . 'PHPMailer.php') &&
        file_exists($local_phpmailer_path . DIRECTORY_SEPARATOR . 'SMTP.php') &&
        file_exists($local_phpmailer_path . DIRECTORY_SEPARATOR . 'Exception.php')
    ) {
        echo "<p class='ok'>✅ Pasta vendor não existe, mas PHPMailer local está disponível.</p>";
        echo "<p><strong>Modo atual:</strong> <code>libraries/PHPMailer/src</code> (sem Composer)</p>";
    } else {
        echo "<p class='erro'>❌ Pasta vendor não existe e PHPMailer local não foi encontrado.</p>";
    }
}
echo "</div>";

// 4. Tentar instanciar PHPMailer
echo "<div class='box'>";
echo "<h2>4. Testando instanciação do PHPMailer</h2>";

try {
    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
    echo "<p class='ok'>✅ PHPMailer instanciado com sucesso!</p>";
    echo "<p><strong>Versão:</strong> " . (defined('PHPMailer\\PHPMailer\\PHPMailer::VERSION') ? PHPMailer\PHPMailer\PHPMailer::VERSION : 'Não disponível') . "</p>";
    
    // Testar configurações básicas
    $mail->isSMTP();
    echo "<p class='ok'>✅ Método isSMTP() funcionando</p>";
    
    unset($mail);
} catch (\Exception $e) {
    echo "<p class='erro'>❌ Erro ao instanciar PHPMailer: " . $e->getMessage() . "</p>";
    
    // Tentar com namespace alternativo
    echo "<p><strong>Tentando namespace alternativo...</strong></p>";
    try {
        if (class_exists('PHPMailer')) {
            $mail = new PHPMailer(true);
            echo "<p class='aviso'>⚠️ Funcionou com namespace: <code>PHPMailer</code></p>";
            echo "<p>Será necessário ajustar o arquivo email.php</p>";
        }
    } catch (\Exception $e2) {
        echo "<p class='erro'>❌ Também falhou: " . $e2->getMessage() . "</p>";
    }
}
echo "</div>";

// 5. Verificar EmailService
echo "<div class='box'>";
echo "<h2>5. Verificando EmailService</h2>";

// Carregar o arquivo email.php que contém a classe EmailService
$email_php_path = ROOT_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'email.php';
if (file_exists($email_php_path)) {
    echo "<p class='ok'>✅ Arquivo email.php encontrado</p>";
    
    try {
        // Carregar o arquivo (pode gerar erros se PHPMailer não estiver instalado, mas não importa)
        ob_start();
        require_once $email_php_path;
        $output = ob_get_clean();
        
        if (class_exists('EmailService')) {
            echo "<p class='ok'>✅ Classe EmailService encontrada!</p>";
            
            try {
                $emailService = new EmailService();
                echo "<p class='ok'>✅ EmailService instanciado com sucesso!</p>";
                
                // Testar se os métodos existem
                if (method_exists($emailService, 'enviarNotificacaoChamadoAberto')) {
                    echo "<p class='ok'>✅ Método enviarNotificacaoChamadoAberto() existe</p>";
                }
                if (method_exists($emailService, 'enviarNotificacaoChamadoFinalizado')) {
                    echo "<p class='ok'>✅ Método enviarNotificacaoChamadoFinalizado() existe</p>";
                }
            } catch (\Exception $e) {
                echo "<p class='erro'>❌ Erro ao instanciar EmailService: " . $e->getMessage() . "</p>";
                echo "<p><small>Detalhes: " . htmlspecialchars($e->getFile()) . " na linha " . $e->getLine() . "</small></p>";
            }
        } else {
            echo "<p class='erro'>❌ Classe EmailService não encontrada após carregar email.php!</p>";
            echo "<p><strong>Possível causa:</strong> A classe não foi definida ou houve erro ao carregar.</p>";
            
            // Verificar classes definidas
            $classes = get_declared_classes();
            $emailClasses = array_filter($classes, function($class) {
                return stripos($class, 'email') !== false || stripos($class, 'mail') !== false;
            });
            if (!empty($emailClasses)) {
                echo "<p><strong>Classes relacionadas encontradas:</strong> " . implode(', ', $emailClasses) . "</p>";
            }
        }
    } catch (\Exception $e) {
        echo "<p class='erro'>❌ Erro ao carregar email.php: " . $e->getMessage() . "</p>";
    } catch (\Error $e) {
        echo "<p class='erro'>❌ Erro fatal ao carregar email.php: " . $e->getMessage() . "</p>";
        echo "<p><small>Arquivo: " . $e->getFile() . " linha " . $e->getLine() . "</small></p>";
    }
} else {
    echo "<p class='erro'>❌ Arquivo email.php não encontrado em: <code>$email_php_path</code></p>";
}
echo "</div>";

// Resumo
echo "<hr>";
echo "<h2>📋 Resumo</h2>";

$todos_ok = (file_exists($autoload_path) || file_exists($local_phpmailer_path . DIRECTORY_SEPARATOR . 'PHPMailer.php')) && 
            class_exists('PHPMailer\\PHPMailer\\PHPMailer');

if ($todos_ok) {
    echo "<div class='box' style='background:#d4edda;border-left:4px solid #28a745;'>";
    echo "<p class='ok'>✅ Tudo está funcionando corretamente!</p>";
    echo "<p>O PHPMailer está instalado e pronto para uso.</p>";
    echo "</div>";
} else {
    echo "<div class='box' style='background:#f8d7da;border-left:4px solid #dc3545;'>";
    echo "<p class='erro'>❌ Há problemas que precisam ser corrigidos.</p>";
    echo "<p><strong>Próximos passos:</strong></p>";
    echo "<ol>";
    echo "<li>Verifique se executou <code>composer install</code> corretamente</li>";
    echo "<li>Se a pasta for <code>vendor/phpmail/phpmail</code>, execute: <code>composer remove phpmailer/phpmailer</code> e depois <code>composer require phpmailer/phpmailer</code></li>";
    echo "<li>Verifique as permissões da pasta vendor</li>";
    echo "</ol>";
    echo "</div>";
}

echo "<p><a href='login.php'>← Voltar</a></p>";
echo "</body></html>";
?>

