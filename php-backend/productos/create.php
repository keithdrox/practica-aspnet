<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../repositories/PdoProductoRepository.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Token CSRF inválido.');
    }

    $nombre = trim($_POST['nombre'] ?? '');
    $precio = (float)($_POST['precio'] ?? 0);
    $descripcion = trim($_POST['descripcion'] ?? '');
    $stock = (int)($_POST['stock'] ?? 0);

    if ($nombre === '' || $precio <= 0) {
        $errores[] = 'Nombre y precio son obligatorios.';
    } else {
        $pdo = getPDOConnection();
        $repo = new PdoProductoRepository($pdo);
        $repo->create($nombre, $precio, $descripcion, $stock);

        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Producto</title>
</head>
<body>
    <h1>Registrar Nuevo Producto</h1>

    <?php foreach ($errores as $error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endforeach; ?>

    <form method="post" action="create.php">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

        <label>Nombre</label>
        <input type="text" name="nombre" required>

        <label>Descripción</label>
        <textarea name="descripcion"></textarea>

        <label>Precio</label>
        <input type="number" name="precio" step="0.01" required>

        <label>Stock</label>
        <input type="number" name="stock" required>

        <button type="submit">Guardar Producto</button>
    </form>

    <a href="index.php">← Volver</a>
</body>
</html>