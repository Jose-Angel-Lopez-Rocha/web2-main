<?php
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

// CONEXIÓN CORRECTA
$conexion = new mysqli("localhost", "root", "", "zapateria", 4306);

if ($conexion->connect_error) {
    echo json_encode(["error" => "Error de conexión"]);
    exit;
}

// RECIBIR NOMBRE
$nombre = $_POST['nombre'] ?? "";

$stmt = $conexion->prepare("SELECT id FROM clientes WHERE nombre = ?");
$stmt->bind_param("s", $nombre);
$stmt->execute();
$stmt->store_result();

// RESPUESTA JSON
echo json_encode([
    "existe" => $stmt->num_rows > 0
]);

$stmt->close();
$conexion->close();
?>
