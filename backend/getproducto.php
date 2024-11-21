<?php
header('Content-Type: application/json');
require_once 'database.php'; // Incluye tu archivo de conexión a la base de datos

// Conexión a la base de datos
$database = new Database();
$conn = $database->getConnection();

// Obtener todos los productos
$sql = "SELECT pro_id, pro_nombre, pro_descripcion, pro_precio FROM producto";
$stmt = $conn->prepare($sql);
$stmt->execute();

// Obtener los resultados
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Devolver los productos en formato JSON
echo json_encode([
    'success' => true,
    'productos' => $productos
]);

// Cerrar conexión
$stmt = null;
$conn = null;
?>
