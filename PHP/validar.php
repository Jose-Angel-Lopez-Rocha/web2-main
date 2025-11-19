<?php
require 'conf_sesion.php';

$email = $_POST['email'] ?? '';
$clave = $_POST['clave'] ?? '';

// Conexión correcta
try {
    $pdo = new PDO(
        "mysql:host=localhost;port=4306;dbname=zapateria;charset=utf8mb4",
        "root",
        ""
    );
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Buscar usuario por email
$stmt = $pdo->prepare("SELECT * FROM clientes WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar credenciales (texto plano)
if ($user && $clave === $user['clave']) {

    // Guardar sesión
    $_SESSION['cliente'] = $user['id'];
    $_SESSION['cliente_nombre'] = $user['nombre'] ?? '';
    $_SESSION['cliente_rol'] = $user['rol'] ?? '';

    // Si marcó "recordarme", crear token seguro
    if (!empty($_POST['recordarme'])) {
        $token = bin2hex(random_bytes(16));
        setcookie("remember_token", $token, time() + (60 * 60 * 24 * 7), "/", "", false, true);

        // Guardar token en la BD
        $stmt = $pdo->prepare("UPDATE clientes SET remember_token = ? WHERE id = ?");
        $stmt->execute([$token, $user['id']]);
    }

    // Redirigir al index
    header("Location: ../HTML/index.php");
    exit();

} else {
    // Para mostrar error en login
    $error = "Correo o contraseña incorrectos";

    // Mostrar login desde carpeta HTML
    include "../HTML/login.php";
    exit();
}
