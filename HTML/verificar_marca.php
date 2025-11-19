<?php
header("Content-Type: application/json");

try {
    $pdo = new PDO(
        "mysql:host=localhost;port=4306;dbname=zapateria;charset=utf8mb4",
        "root",
        "",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Error de conexión a la base de datos"]);
    exit;
}

// Leer JSON del body
$datos = json_decode(file_get_contents("php://input"), true);

$marca = $datos['marca'] ?? "";
$talla = $datos['talla'] ?? "";
$color = $datos['color'] ?? "";
$stock = $datos['stock'] ?? 0;
$precio = $datos['precio'] ?? 0;
$categoria = $datos['categoria'] ?? "";

// Validar que marca no esté vacía
if (!$marca) {
    echo json_encode(["success" => false, "message" => "La marca es obligatoria"]);
    exit;
}

// Insertar en la base de datos
try {
    $stmt = $pdo->prepare("INSERT INTO calzado (marca, talla, color, stock, precio, categoria) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$marca, $talla, $color, $stock, $precio, $categoria]);

    echo json_encode([
        "success" => true,
        "id" => $pdo->lastInsertId(),
        "message" => "Calzado registrado correctamente"
    ]);
} catch (PDOException $e) {
    if ($e->getCode() == 23000) { // UNIQUE duplicado
        echo json_encode(["success" => false, "message" => "La marca '$marca' ya está registrada"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error de base de datos: " . $e->getMessage()]);
    }
}
