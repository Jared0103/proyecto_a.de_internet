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
    $producto_nombre = $data['producto_nombre'] ?? '';
    $producto_descripcion = $data['producto_descripcion'] ?? '';
    $producto_precio = $data['producto_precio'] ?? '';
    $accion = $data['accion'] ?? '';
    $cantidad = $data['cantidad'] ?? '';
    $admin_id = $data['admin_id'] ?? '';

    // Verificar que los datos no estén vacíos
    if (empty($producto_nombre) || empty($producto_descripcion) || empty($producto_precio) || empty($accion) || empty($cantidad)) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos']);
        exit;
    }

    
    $sql_check_producto = "SELECT pro_id FROM producto WHERE pro_nombre = :producto_nombre";
    $stmt_check_producto = $conn->prepare($sql_check_producto);
    $stmt_check_producto->bindParam(':producto_nombre', $producto_nombre);
    $stmt_check_producto->execute();

    if ($stmt_check_producto->rowCount() == 0) {
        $sql_insert_producto = "INSERT INTO producto (pro_nombre, pro_descripcion, pro_precio) VALUES (:producto_nombre, :producto_descripcion, :producto_precio)";
        $stmt_insert_producto = $conn->prepare($sql_insert_producto);
        $stmt_insert_producto->bindParam(':producto_nombre', $producto_nombre);
        $stmt_insert_producto->bindParam(':producto_descripcion', $producto_descripcion);
        $stmt_insert_producto->bindParam(':producto_precio', $producto_precio);

        if ($stmt_insert_producto->execute()) {
            $producto_id = $conn->lastInsertId();
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al insertar el producto']);
            exit;
        }
    } else {
        $producto_id = $stmt_check_producto->fetchColumn();
    }

    $sql_insert_inventario = "INSERT INTO inventario (inv_fecha, inv_accion, inv_cantidad, inv_pro_id) 
                              VALUES (NOW(), :accion, :cantidad, :producto_id)";
    $stmt_insert_inventario = $conn->prepare($sql_insert_inventario);
    $stmt_insert_inventario->bindParam(':accion', $accion);
    $stmt_insert_inventario->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
    $stmt_insert_inventario->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);

    if ($stmt_insert_inventario->execute()) {
        echo json_encode([ 
            'success' => true,
            'message' => 'Producto agregado al inventario',
            'producto' => [
                'id' => $producto_id,
                'nombre' => $producto_nombre,
                'descripcion' => $producto_descripcion,
                'precio' => $producto_precio,
                'cantidad' => $cantidad,
                'accion' => $accion
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al agregar producto al inventario']);
    }

    $stmt_check_producto = null;
    $stmt_insert_producto = null;
    $stmt_insert_inventario = null;
    $conn = null;
}
?>