<?php
$conexion = new mysqli("localhost", "usuario_vendedor", "", "zapateria", 4306);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
echo "Conexión exitosa con MySQLi en puerto 4306";




?>
