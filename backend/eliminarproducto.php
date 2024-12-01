<?php
header('Content-Type: application/json');
include 'database.php'; // Asegúrate de que este archivo contiene la conexión PDO en $conn

$database = new Database();
$conn = $database->getConnection();

// Obtener los datos de la solicitud
$data = json_decode(file_get_contents('php://input'), true);
$usuarioId = $data['usuarioId'] ?? null;
$productoId = $data['productoId'] ?? null;

// Verifica si los parámetros están correctamente definidos
if (!$usuarioId || !$productoId) {
    echo json_encode(['success' => false, 'message' => 'Usuario o producto no especificado.']);
    exit;
}

try {
    // Verifica si el carrito está abierto
    $queryCarrito = "SELECT car_id FROM carrito WHERE car_usu_id = :usuarioId AND car_estado = 'abierto'";
    $stmtCarrito = $conn->prepare($queryCarrito);
    $stmtCarrito->bindParam(':usuarioId', $usuarioId, PDO::PARAM_INT);
    $stmtCarrito->execute();
    $carrito = $stmtCarrito->fetch(PDO::FETCH_ASSOC);

    if (!$carrito) {
        echo json_encode(['success' => false, 'message' => 'El carrito no está abierto o no existe.']);
        exit;
    }

    // Verifica si el producto está en el carrito
    $queryDetalle = "SELECT * FROM detallecarrito WHERE det_car_id = :carritoId AND det_pro_id = :productoId";
    $stmtDetalle = $conn->prepare($queryDetalle);
    $stmtDetalle->bindParam(':carritoId', $carrito['car_id'], PDO::PARAM_INT);
    $stmtDetalle->bindParam(':productoId', $productoId, PDO::PARAM_INT);
    $stmtDetalle->execute();

    if ($stmtDetalle->rowCount() == 0) {
        echo json_encode(['success' => false, 'message' => 'No se encontró el producto en el carrito para el usuario.']);
        exit;
    }

    // Procede con la eliminación
    $queryEliminar = "DELETE FROM detallecarrito WHERE det_car_id = :carritoId AND det_pro_id = :productoId";
    $stmtEliminar = $conn->prepare($queryEliminar);
    $stmtEliminar->bindParam(':carritoId', $carrito['car_id'], PDO::PARAM_INT);
    $stmtEliminar->bindParam(':productoId', $productoId, PDO::PARAM_INT);
    $stmtEliminar->execute();

    if ($stmtEliminar->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Producto eliminado con éxito.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el producto del carrito.']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

?>

