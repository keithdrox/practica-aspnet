<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../repositories/PdoProductoRepository.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Token CSRF inválido.');
    }

    $id = (int)($_POST['id'] ?? 0);

    $pdo = getPDOConnection();
    $repo = new PdoProductoRepository($pdo);
    $repo->delete($id);
}

header('Location: index.php');
exit;