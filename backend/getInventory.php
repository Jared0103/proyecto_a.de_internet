<?php
// Conexión a la base de datos
require_once 'db.php';

// Consultar los productos en el inventario
$sql = "SELECT p.pro_nombre, i.inv_accion, i.inv_cantiad, i.inv_fecha
        FROM inventario i
        JOIN producto p ON i.inv_pro_id = p.pro_id
        ORDER BY i.inv_fecha DESC";

$result = $conn->query($sql);

// Verificar si hay resultados
if ($result->num_rows > 0) {
    $inventory = [];
    while ($row = $result->fetch_assoc()) {
        $inventory[] = $row;
    }
    echo json_encode($inventory);
} else {
    echo json_encode([]);
}

$conn->close();
?>
