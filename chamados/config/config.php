<?php
// Configurações Gerais do Sistema

// Configuração de Sessão
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 1 : 0);
ini_set('session.cookie_samesite', 'Lax');
session_start();

// Timezone
date_default_timezone_set('America/Sao_Paulo');

// Configuração local (não versionada)
$localConfigPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'local.php';
if (file_exists($localConfigPath)) {
    require_once $localConfigPath;
}

// Configuração de E-mail
define('SMTP_HOST', getenv('SMTP_HOST') ?: 'smtp.hostinger.com');
define('SMTP_PORT', (int) (getenv('SMTP_PORT') ?: 465));
define('SMTP_ENCRYPTION', strtolower((string) (getenv('SMTP_ENCRYPTION') ?: '')));
define('SMTP_USER', getenv('SMTP_USER') ?: '');
define('SMTP_PASS', getenv('SMTP_PASS') ?: '');
define('SMTP_FROM_EMAIL', getenv('SMTP_FROM_EMAIL') ?: SMTP_USER);
define('SMTP_FROM_NAME', getenv('SMTP_FROM_NAME') ?: 'Sistema de Chamados T.I.');
define('SUPPORT_NOTIFICATION_EMAIL', getenv('SUPPORT_NOTIFICATION_EMAIL') ?: '');

// URL Base do Sistema
if (!defined('BASE_URL')) {
    $baseUrlFromEnv = getenv('BASE_URL');

    if (!empty($baseUrlFromEnv)) {
        $baseUrl = rtrim((string) $baseUrlFromEnv, '/') . '/';
    } else {
        $isHttps = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
        $scheme = $isHttps ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $basePath = str_replace('\\', '/', dirname($scriptName));
        $basePath = trim($basePath, '/');

        $baseUrl = $scheme . '://' . $host . '/';
        if ($basePath !== '' && $basePath !== '.') {
            $baseUrl .= $basePath . '/';
        }
    }

    define('BASE_URL', $baseUrl);
}

// Caminhos
define('ROOT_PATH', dirname(__DIR__));
define('UPLOAD_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR);
define('UPLOAD_URL', BASE_URL . 'uploads/');

// Criar pasta de uploads se não existir
if (!file_exists(UPLOAD_PATH)) {
    mkdir(UPLOAD_PATH, 0777, true);
}

// Incluir autoload
require_once ROOT_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'database.php';
require_once ROOT_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'functions.php';

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    validateCsrfOrAbort();
}

