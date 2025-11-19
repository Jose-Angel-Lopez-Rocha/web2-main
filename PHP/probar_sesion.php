<?php
require 'conf_sesion.php'; // o la ruta correcta

if (isset($_SESSION['cliente'])) {
    echo "Sesión activa. ID: " . $_SESSION['cliente'] . "<br>";
    echo "Nombre: " . ($_SESSION['cliente_nombre'] ?? '');
} else {
    echo "No hay sesión activa.<br>";
}

// Mostrar cookie remember_token
if (isset($_COOKIE['remember_token'])) {
    echo "Cookie remember_token: " . $_COOKIE['remember_token'];
} else {
    echo "No hay cookie remember_token.";
}
