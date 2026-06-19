<?php
require_once __DIR__ . '/../includes/security_headers.php';
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../repositories/PdoProductoRepository.php';

$pdo = getPDOConnection();
$repo = new PdoProductoRepository($pdo);
$productos = $repo->findAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Productos - PHP</title>
</head>
<body>
    <p>Sesión: <?= htmlspecialchars($_SESSION['user_name']) ?> | <a href="/practica-php/logout.php">Cerrar Sesión</a></p>

    <h1>Catálogo de Productos</h1>
    <a href="create.php">+ Nuevo Producto</a>

    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th><th>Nombre</th><th>Descripción</th><th>Precio</th><th>Stock</th><th>Acciones</th>
        </tr>
        <?php foreach ($productos as $p): ?>
        <tr>
            <td><?= htmlspecialchars((string)$p['Id']) ?></td>
            <td><?= htmlspecialchars($p['Nombre']) ?></td>
            <td><?= htmlspecialchars($p['Descripcion'] ?? '') ?></td>
            <td>$<?= htmlspecialchars(number_format((float)$p['Precio'], 2)) ?></td>
            <td><?= htmlspecialchars((string)$p['Stock']) ?></td>
            <td>
                <form method="post" action="delete.php" style="display:inline;">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                    <input type="hidden" name="id" value="<?= (int)$p['Id'] ?>">
                    <button type="submit">Eliminar</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>