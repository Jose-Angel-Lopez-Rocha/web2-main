<?php
// --------------------------
// conf_sesion.php
// --------------------------

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,        // dura hasta cerrar el navegador
        'path'     => '/',
        'secure'   => true,    
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

try {
    $pdo = new PDO(
        "mysql:host=localhost;port=4306;dbname=zapateria;charset=utf8mb4",
        "root",
        ""
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

if (!isset($_SESSION['cliente']) && !empty($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    $stmt = $pdo->prepare("SELECT * FROM clientes WHERE remember_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['cliente'] = $user['id'];
        $_SESSION['cliente_nombre'] = $user['nombre'] ?? '';
        $_SESSION['cliente_rol'] = $user['rol'] ?? '';
    } else {
        // Si el token no es válido, borrar cookie
        setcookie("remember_token", "", time() - 3600, "/");
    }
}

function requireLogin() {
    if (!isset($_SESSION['cliente'])) {
        header("Location: ../HTML/login.php");
        exit();
    }
}
