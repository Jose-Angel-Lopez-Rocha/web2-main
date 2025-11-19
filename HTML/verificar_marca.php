<?php
header("Content-Type: application/json");

$conexion = new mysqli("localhost", "root", "", "zapateria", 4306);

if ($conexion->connect_error) {
    echo json_encode(["error" => "Error de conexión"]);
    exit;
}

$marca = $_POST["marca"] ?? "";

$stmt = $conexion->prepare("SELECT id FROM calzado WHERE marca = ?");
$stmt->bind_param("s", $marca);
$stmt->execute();
$stmt->store_result();

echo json_encode([
    "existe" => $stmt->num_rows > 0
]);

$stmt->close();
$conexion->close();
