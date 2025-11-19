<?php

require 'conexion.php';

// Si NO hay sesión activa
if (!isset($_SESSION['user_id'])) {

    // ¿Existe cookie remember?
    if (isset($_COOKIE['remember_token'])) {

        $token = $_COOKIE['remember_token'];

        $sql = "SELECT id FROM clientes WHERE remember_token = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        // Si el token es válido → recrear sesión
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $_SESSION['user_id'] = $user['id'];
        }
    }
}

// Si después de todo NO hay sesión → bloquear acceso
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
