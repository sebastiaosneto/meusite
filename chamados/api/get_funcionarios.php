<?php
require_once '../config/config.php';
requireLogin();
checkUserType(['admin', 'tecnico']);

header('Content-Type: application/json');

$empresa_id = $_GET['empresa_id'] ?? null;

if (!$empresa_id) {
    echo json_encode([]);
    exit;
}

$db = Database::getInstance()->getConnection();
$stmt = $db->prepare("SELECT id, nome FROM usuarios WHERE tipo = 'funcionario' AND empresa_id = ? AND ativo = 1 ORDER BY nome");
$stmt->execute([$empresa_id]);
$funcionarios = $stmt->fetchAll();

echo json_encode($funcionarios);

