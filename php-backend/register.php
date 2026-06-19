<?php
require_once __DIR__ . '/config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombreUsuario = trim($_POST['nombre_usuario'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmar = $_POST['confirmar_password'] ?? '';

    if ($nombreUsuario === '' || $email === '' || $password === '') {
        $errores[] = 'Todos los campos son obligatorios.';
    }
    if ($password !== $confirmar) {
        $errores[] = 'Las contraseñas no coinciden.';
    }
    if (strlen($password) < 6) {
        $errores[] = 'La contraseña debe tener al menos 6 caracteres.';
    }

    if (empty($errores)) {
        $pdo = getPDOConnection();

        $check = $pdo->prepare('SELECT "Id" FROM "Usuarios" WHERE "Email" = :email');
        $check->bindValue(':email', $email, PDO::PARAM_STR);
        $check->execute();

        if ($check->fetch()) {
            $errores[] = 'Este correo ya está registrado.';
        } else {
            $hash = password_hash($password, PASSWORD_ARGON2ID);

            $insert = $pdo->prepare(
                'INSERT INTO "Usuarios" ("NombreUsuario", "Email", "PasswordHash", "FechaRegistro") VALUES (:nombre, :email, :hash, NOW())'
            );
            $insert->bindValue(':nombre', $nombreUsuario, PDO::PARAM_STR);
            $insert->bindValue(':email', $email, PDO::PARAM_STR);
            $insert->bindValue(':hash', $hash, PDO::PARAM_STR);
            $insert->execute();

            header('Location: /practica-php/login.php?registrado=1');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - MiTienda PHP</title>
</head>
<body>
    <h1>Crear Cuenta</h1>

    <?php foreach ($errores as $error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endforeach; ?>

    <form method="post" action="register.php">
        <label>Nombre de Usuario</label>
        <input type="text" name="nombre_usuario" required>

        <label>Correo Electrónico</label>
        <input type="email" name="email" required>

        <label>Contraseña</label>
        <input type="password" name="password" required>

        <label>Confirmar Contraseña</label>
        <input type="password" name="confirmar_password" required>

        <button type="submit">Registrarme</button>
    </form>

    <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
</body>
</html>