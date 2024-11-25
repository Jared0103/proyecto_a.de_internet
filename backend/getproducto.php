<?php
header('Content-Type: application/json');
require_once 'database.php'; // Incluye tu archivo de conexión a la base de datos

// Conexión a la base de datos
$database = new Database();
$conn = $database->getConnection();

// Consulta para obtener los productos
$sql = "SELECT pro_id, pro_nombre, pro_descripcion, pro_precio FROM producto";
$stmt = $conn->prepare($sql);
$stmt->execute();

// Obtener los resultados
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verifica si se encontraron productos
if ($productos) {
    // Si hay productos, devolverlos en formato JSON
    echo json_encode([
        'success' => true,
        'productos' => $productos
    ]);
} else {
    // Si no se encontraron productos, devolver un mensaje
    echo json_encode([
        'success' => false,
        'message' => 'No se encontraron productos'
    ]);
}

// Cerrar conexión
$stmt = null;
$conn = null;
?>
