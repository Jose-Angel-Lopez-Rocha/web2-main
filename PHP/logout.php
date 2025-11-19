<?php
require 'conf_sesion.php';

// Guardar el ID del usuario antes de destruir la sesión
$user_id = $_SESSION['cliente'] ?? null;

// Limpiar todas las variables de sesión
$_SESSION = [];

// Borrar cookie de sesión de PHP
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Destruir la sesión en el servidor
session_destroy();

// Borrar cookie personalizada "remember_token"
setcookie("remember_token", "", time() - 3600, "/");

// Limpiar token en la base de datos
if ($user_id) {
    $stmt = $pdo->prepare("UPDATE clientes SET remember_token = NULL WHERE id = ?");
    $stmt->execute([$user_id]);
}

// Redirigir al login
header("Location: ../HTML/login.php");
exit();
