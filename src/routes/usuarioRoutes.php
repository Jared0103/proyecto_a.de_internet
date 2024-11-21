<?php
require_once '../controllers/UsuarioController.php';

$usuarioController = new UsuarioController();

// Rutas para usuario
$router->post('/usuario', function() use ($usuarioController) {
    $data = json_decode(file_get_contents('php://input'), true);
    $usuarioController->registrarUsuario($data);
});

$router->get('/usuario/{correo}', function($correo) use ($usuarioController) {
    $usuarioController->obtenerUsuario($correo);
});

$router->put('/usuario/{id}', function($id) use ($usuarioController) {
    $data = json_decode(file_get_contents('php://input'), true);
    $usuarioController->actualizarUsuario($id, $data);
});

$router->delete('/usuario/{id}', function($id) use ($usuarioController) {
    $usuarioController->eliminarUsuario($id);
});
?>
