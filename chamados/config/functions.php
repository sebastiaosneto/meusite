<?php
// Funções Auxiliares

// Sanitizar entrada
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

// Verificar se usuário está logado
function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['user_type']);
}

// Verificar tipo de usuário
function checkUserType($allowedTypes) {
    if (!isLoggedIn()) {
        header('Location: ' . BASE_URL . 'login.php');
        exit;
    }
    
    if (!in_array($_SESSION['user_type'], $allowedTypes)) {
        header('Location: ' . BASE_URL . 'index.php');
        exit;
    }
}

// Redirecionar se não estiver logado
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . BASE_URL . 'login.php');
        exit;
    }
}

// Gerar token CSRF
function csrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

// Validar token CSRF
function isValidCsrfToken($token) {
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }

    return hash_equals($_SESSION['csrf_token'], $token);
}

// Bloquear requisições POST sem CSRF válido
function validateCsrfOrAbort() {
    $token = $_POST['csrf_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');

    if (!isValidCsrfToken($token)) {
        http_response_code(419);
        exit('Token de seguranca invalido. Atualize a pagina e tente novamente.');
    }
}

// Obter dados do usuário logado
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

// Formatar data
function formatDate($date, $format = 'd/m/Y H:i') {
    if (empty($date)) return '-';
    $dateObj = new DateTime($date);
    return $dateObj->format($format);
}

// Formatar status
function formatStatus($status) {
    $statusLabels = [
        'pendente' => 'Pendente',
        'em_atendimento' => 'Em Atendimento',
        'finalizado' => 'Finalizado',
        'reaberto' => 'Reaberto',
        'cancelado' => 'Cancelado'
    ];
    return $statusLabels[$status] ?? $status;
}

// Formatar prioridade
function formatPrioridade($prioridade) {
    $prioridadeLabels = [
        'baixa' => 'Baixa',
        'media' => 'Média',
        'alta' => 'Alta',
        'urgente' => 'Urgente'
    ];
    return $prioridadeLabels[$prioridade] ?? $prioridade;
}

// Obter classe CSS para status
function getStatusClass($status) {
    $classes = [
        'pendente' => 'badge-warning',
        'em_atendimento' => 'badge-info',
        'finalizado' => 'badge-success',
        'reaberto' => 'badge-danger',
        'cancelado' => 'badge-secondary'
    ];
    return $classes[$status] ?? 'badge-secondary';
}

// Obter classe CSS para prioridade
function getPrioridadeClass($prioridade) {
    $classes = [
        'baixa' => 'badge-info',
        'media' => 'badge-primary',
        'alta' => 'badge-warning',
        'urgente' => 'badge-danger'
    ];
    return $classes[$prioridade] ?? 'badge-secondary';
}

// Upload de arquivo
function uploadFile($file, $prefix = '') {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    $maxSize = 5 * 1024 * 1024; // 5MB
    
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $detectedType = $finfo ? finfo_file($finfo, $file['tmp_name']) : '';
    if ($finfo) {
        finfo_close($finfo);
    }

    if (!in_array($detectedType, $allowedTypes)) {
        return ['error' => 'Tipo de arquivo não permitido'];
    }
    
    if ($file['size'] > $maxSize) {
        return ['error' => 'Arquivo muito grande. Máximo 5MB'];
    }
    
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowedExtensions = [
        'image/jpeg' => ['jpg', 'jpeg'],
        'image/png' => ['png'],
        'image/gif' => ['gif'],
        'application/pdf' => ['pdf'],
        'application/msword' => ['doc'],
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => ['docx']
    ];

    if (!isset($allowedExtensions[$detectedType]) || !in_array($extension, $allowedExtensions[$detectedType], true)) {
        return ['error' => 'Extensão de arquivo inválida para o tipo informado'];
    }

    $filename = $prefix . '_' . time() . '_' . uniqid() . '.' . $extension;
    $filepath = UPLOAD_PATH . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return $filename;
    }
    
    return ['error' => 'Erro ao fazer upload do arquivo'];
}

// Deletar arquivo
function deleteFile($filename) {
    if (!empty($filename)) {
        $filepath = UPLOAD_PATH . $filename;
        if (file_exists($filepath)) {
            unlink($filepath);
        }
    }
}

