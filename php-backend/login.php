<?php
require_once __DIR__ . '/config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $pdo = getPDOConnection();
    $stmt = $pdo->prepare('SELECT "Id", "NombreUsuario", "Email", "PasswordHash" FROM "Usuarios" WHERE "Email" = :email');
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($password, $usuario['PasswordHash'])) {
        session_regenerate_id(true);

        $_SESSION['user_id'] = $usuario['Id'];
        $_SESSION['user_name'] = $usuario['NombreUsuario'];

        header('Location: /practica-php/productos/index.php');
        exit;
    } else {
        $error = 'Correo o contraseña incorrectos.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - MiTienda PHP</title>
</head>
<body>
    <h1>Iniciar Sesión</h1>

    <?php if (isset($_GET['registrado'])): ?>
        <p style="color:green;">Registro exitoso. Ahora puedes iniciar sesión.</p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" action="login.php">
        <label>Correo Electrónico</label>
        <input type="email" name="email" required>

        <label>Contraseña</label>
        <input type="password" name="password" required>

        <button type="submit">Ingresar</button>
    </form>

    <p>¿No tienes cuenta? <a href="register.php">Regístrate aquí</a></p>
</body>
</html>