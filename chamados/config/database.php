<?php
// Configuração do Banco de Dados

define('DB_HOST', 'localhost');
define('DB_NAME', 'u647286339_sys_chamados');
define('DB_USER', 'u647286339_chamados');
define('DB_PASS', 'K9f;PGE!AG');
define('DB_CHARSET', 'utf8mb4');

// Conexão com o banco de dados
class Database {
    private static $instance = null;
    private $conn;
    
    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $this->conn = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch(PDOException $e) {
            // Em desenvolvimento, mostrar erro detalhado
            if (ini_get('display_errors')) {
                die("Erro na conexão com o banco de dados: " . $e->getMessage() . 
                    "<br><br><strong>Verifique:</strong><br>" .
                    "1. Se o banco de dados 'sistema_chamados' existe<br>" .
                    "2. Se o MySQL está rodando<br>" .
                    "3. Se as credenciais em config/database.php estão corretas<br>" .
                    "4. Importe o arquivo database.sql no phpMyAdmin");
            } else {
                error_log("Erro na conexão: " . $e->getMessage());
                die("Erro na conexão com o banco de dados. Verifique os logs.");
            }
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->conn;
    }
    
    // Prevenir clonagem
    private function __clone() {}
    
    // Prevenir unserialize
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}

