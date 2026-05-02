<?php
/**
 * Script para Corrigir Usuário Admin Automaticamente
 * Este script cria/atualiza o usuário admin com a senha correta
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/config.php';

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Corrigir Admin</title>";
echo "<style>body{font-family:Arial;padding:20px;max-width:800px;margin:0 auto;}";
echo ".ok{color:green;font-weight:bold;} .erro{color:red;font-weight:bold;}";
echo ".box{background:#f5f5f5;padding:20px;border-radius:5px;margin:20px 0;}";
echo "pre{background:#fff;padding:15px;border:1px solid #ddd;border-radius:3px;}</style>";
echo "</head><body>";

echo "<h1>🔧 Correção Automática do Usuário Admin</h1>";

$usuario = 'admin';
$senha = 'admin123';
$hash = password_hash($senha, PASSWORD_BCRYPT);

echo "<div class='box'>";
echo "<h2>Informações:</h2>";
echo "<p><strong>Usuário:</strong> $usuario</p>";
echo "<p><strong>Senha:</strong> $senha</p>";
echo "<p><strong>Hash gerado:</strong> <code style='word-break:break-all;'>$hash</code></p>";
echo "</div>";

try {
    $db = Database::getInstance()->getConnection();
    
    // Verificar se usuário existe
    $stmt = $db->prepare("SELECT id FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $existe = $stmt->fetch();
    
    if ($existe) {
        echo "<h2>Atualizando usuário existente...</h2>";
        
        $stmt = $db->prepare("
            UPDATE usuarios 
            SET nome = ?,
                email = ?,
                senha = ?,
                tipo = ?,
                ativo = 1,
                updated_at = NOW()
            WHERE usuario = ?
        ");
        
        $resultado = $stmt->execute([
            'Administrador',
            'admin@sistema.com',
            $hash,
            'admin',
            $usuario
        ]);
        
        if ($resultado) {
            echo "<p class='ok'>✅ Usuário atualizado com sucesso!</p>";
        } else {
            echo "<p class='erro'>❌ Erro ao atualizar usuário.</p>";
        }
    } else {
        echo "<h2>Criando novo usuário...</h2>";
        
        $stmt = $db->prepare("
            INSERT INTO usuarios (nome, email, usuario, senha, tipo, ativo, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, 1, NOW(), NOW())
        ");
        
        $resultado = $stmt->execute([
            'Administrador',
            'admin@sistema.com',
            $usuario,
            $hash,
            'admin'
        ]);
        
        if ($resultado) {
            echo "<p class='ok'>✅ Usuário criado com sucesso!</p>";
        } else {
            echo "<p class='erro'>❌ Erro ao criar usuário.</p>";
        }
    }
    
    // Verificar se funcionou
    echo "<h2>Verificando...</h2>";
    $stmt = $db->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $user = $stmt->fetch();
    
    if ($user) {
        echo "<p class='ok'>✅ Usuário encontrado no banco!</p>";
        echo "<p><strong>ID:</strong> " . $user['id'] . "</p>";
        echo "<p><strong>Nome:</strong> " . $user['nome'] . "</p>";
        echo "<p><strong>Email:</strong> " . $user['email'] . "</p>";
        echo "<p><strong>Tipo:</strong> " . $user['tipo'] . "</p>";
        echo "<p><strong>Ativo:</strong> " . ($user['ativo'] ? 'Sim' : 'Não') . "</p>";
        echo "<p><strong>Tamanho do hash:</strong> " . strlen($user['senha']) . " caracteres</p>";
        
        // Testar senha
        if (password_verify($senha, $user['senha'])) {
            echo "<p class='ok'>✅ Senha verificada com SUCESSO! O login deve funcionar agora.</p>";
        } else {
            echo "<p class='erro'>❌ Erro: A senha ainda não confere. Pode haver problema com o campo no banco.</p>";
            echo "<p><strong>Verifique:</strong> A coluna 'senha' deve ser VARCHAR(255) ou maior.</p>";
        }
    }
    
    echo "<hr>";
    echo "<h2>📋 Próximos Passos</h2>";
    echo "<ol>";
    echo "<li>Teste o login com: <strong>admin</strong> / <strong>admin123</strong></li>";
    echo "<li>Se ainda não funcionar, acesse <code>debug_login.php</code> para mais detalhes</li>";
    echo "<li>Após confirmar que funciona, <strong>delete este arquivo</strong> por segurança</li>";
    echo "</ol>";
    
    echo "<p><a href='login.php' style='display:inline-block;margin-top:20px;padding:10px 20px;background:#0066cc;color:white;text-decoration:none;border-radius:5px;'>Testar Login</a></p>";
    
} catch (Exception $e) {
    echo "<p class='erro'>❌ Erro: " . $e->getMessage() . "</p>";
    echo "<p>Verifique a conexão com o banco de dados.</p>";
}

echo "</body></html>";
?>

