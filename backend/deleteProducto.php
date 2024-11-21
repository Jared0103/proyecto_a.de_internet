<?php
header('Content-Type: application/json');
require_once 'database.php'; // Incluye tu archivo de conexión a la base de datos

// Conexión a la base de datos
$database = new Database();
$conn = $database->getConnection();

// Recibe los datos de la solicitud (en formato JSON)
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// Validación de la solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $producto_id = $data['producto_id'] ?? '';

    // Verificar que el ID del producto no esté vacío
    if (empty($producto_id)) {
        echo json_encode(['success' => false, 'message' => 'Falta el ID del producto']);
        exit;
    }

    // Eliminar el producto de la tabla producto
    $sql_delete_producto = "DELETE FROM producto WHERE pro_id = :producto_id";
    $stmt_delete_producto = $conn->prepare($sql_delete_producto);
    $stmt_delete_producto->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);

    if ($stmt_delete_producto->execute()) {
        // Eliminar el registro correspondiente del inventario (opcional)
        $sql_delete_inventario = "DELETE FROM inventario WHERE inv_pro_id = :producto_id";
        $stmt_delete_inventario = $conn->prepare($sql_delete_inventario);
        $stmt_delete_inventario->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
        $stmt_delete_inventario->execute();

        echo json_encode(['success' => true, 'message' => 'Producto eliminado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar el producto']);
    }

    // Cerrar conexiones
    $stmt_delete_producto = null;
    $stmt_delete_inventario = null;
    $conn = null;
}
?>
