<?php
require_once __DIR__ . '/../controllers/AdministradorController.php';

function administradorRoutes($app, $conn) {
    $controller = new AdministradorController($conn);

    $app->get('/administradores', function() use ($controller) {
        echo json_encode($controller->obtenerTodos());
    });

    $app->get('/administrador/{id}', function($id) use ($controller) {
        echo json_encode($controller->obtenerPorId($id));
    });

    $app->post('/administrador', function() use ($controller) {
        $data = json_decode(file_get_contents("php://input"), true);
        echo json_encode($controller->crear($data));
    });

    $app->put('/administrador/{id}', function($id) use ($controller) {
        $data = json_decode(file_get_contents("php://input"), true);
        echo json_encode($controller->actualizar($id, $data));
    });

    $app->delete('/administrador/{id}', function($id) use ($controller) {
        echo json_encode($controller->eliminar($id));
    });
}
?>
