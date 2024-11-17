<?php
require_once __DIR__ . '/../controllers/CarritoController.php';

function carritoRoutes($app, $conn) {
    $controller = new CarritoController($conn);

    $app->get('/carritos', function() use ($controller) {
        echo json_encode($controller->obtenerTodos());
    });

    $app->get('/carrito/{id}', function($id) use ($controller) {
        echo json_encode($controller->obtenerPorId($id));
    });

    $app->post('/carrito', function() use ($controller) {
        $data = json_decode(file_get_contents("php://input"), true);
        echo json_encode($controller->crear($data));
    });

    $app->put('/carrito/{id}', function($id) use ($controller) {
        $data = json_decode(file_get_contents("php://input"), true);
        echo json_encode($controller->actualizar($id, $data));
    });

    $app->delete('/carrito/{id}', function($id) use ($controller) {
        echo json_encode($controller->eliminar($id));
    });

    $app->get('/carrito/{id}/detalles', function($id) use ($controller) {
        echo json_encode($controller->obtenerDetalles($id));
    });
}
?>
