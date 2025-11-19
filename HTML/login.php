<?php
require '../PHP/conf_sesion.php';

// Si ya hay sesión, ir al index
if (!empty($_SESSION['cliente'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="../CSS/login.css" rel="stylesheet">
</head>
<body>
    <form action="../PHP/validar.php" method="post">
        <h1>Iniciar Sesión</h1>

        <?php if (isset($error)) { ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php } ?>

        <p><input type="email" name="email" placeholder="Correo" required></p>
        <p><input type="password" name="clave" placeholder="Contraseña" required></p>

        <p>
            <label>
                <input type="checkbox" name="recordarme"> Mantener sesión activa
            </label>
        </p>

        <input type="submit" value="Ingresar">
        <p><a href="crear_login.php">Crear cuenta</a></p>
    </form>
</body>
</html>
