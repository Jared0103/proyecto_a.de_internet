<?php
require_once '../controllers/ProductoController.php';

$productoController = new ProductoController();

// Rutas para Producto
$router->post('/producto', function() use ($productoController) {
    $data = json_decode(file_get_contents('php://input'), true);
    $productoController->crearProducto($data);
});

$router->get('/productos', function() use ($productoController) {
    echo json_encode($productoController->obtenerProductos());
});

$router->delete('/producto/{id}', function($id) use ($productoController) {
    $productoController->eliminarProducto($id);
});
?>
