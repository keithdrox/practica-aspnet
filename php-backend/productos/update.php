<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../repositories/PdoProductoRepository.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$pdo = getPDOConnection();
$repo = new PdoProductoRepository($pdo);

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$producto = $repo->findById($id);

if (!$producto) {
    die('Producto no encontrado.');
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
        $repo->update($id, $nombre, $precio, $descripcion, $stock);
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
</head>
<body>
    <h1>Editar Producto</h1>

    <?php foreach ($errores as $error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endforeach; ?>

    <form method="post" action="update.php?id=<?= (int)$id ?>">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
        <input type="hidden" name="id" value="<?= (int)$id ?>">

        <label>Nombre</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($producto['Nombre']) ?>" required>

        <label>Descripción</label>
        <textarea name="descripcion"><?= htmlspecialchars($producto['Descripcion'] ?? '') ?></textarea>

        <label>Precio</label>
        <input type="number" name="precio" step="0.01" value="<?= htmlspecialchars((string)$producto['Precio']) ?>" required>

        <label>Stock</label>
        <input type="number" name="stock" value="<?= htmlspecialchars((string)$producto['Stock']) ?>" required>

        <button type="submit">Guardar Cambios</button>
    </form>

    <a href="index.php">← Volver</a>
</body>
</html>