<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/src/config/database.php';
$conn = getConnection();

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];
$segments = explode('/', trim($requestUri, '/'));
$controller = $segments[1] ?? '';
$id = $segments[2] ?? null;

require_once __DIR__ . './src/controllers/productoController.php';
require_once __DIR__ . './src/controllers/usuarioController.php';
require_once __DIR__ . './src/controllers/adminController.php';
require_once __DIR__ . './src/controllers/carritoController.php';

switch ($controller) {
    case 'producto':
        $productoController = new ProductoController();
        switch ($requestMethod) {
            case 'GET':
                $productos = $productoController->obtenerProductos();
                http_response_code(200);
                echo json_encode($productos);
                break;
            case 'POST':
                if (!empty($data)) {
                    $productoController->crearProducto($data);
                    http_response_code(201);
                    echo json_encode(["message" => "Producto creado con éxito"]);
                } else {
                    http_response_code(400);
                    echo json_encode(["message" => "Datos incompletos"]);
                }
                break;
            case 'DELETE':
                if (!empty($id)) {
                    $productoController->eliminarProducto($id);
                    http_response_code(200);
                    echo json_encode(["message" => "Producto eliminado con éxito"]);
                } else {
                    http_response_code(400);
                    echo json_encode(["message" => "ID no proporcionado"]);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
        }
        break;

    case 'usuario':
        $usuarioController = new UsuarioController();
        switch ($requestMethod) {
            case 'GET':
                if (!empty($id)) {
                    $usuarioController->obtenerUsuario($id);
                } else {
                    http_response_code(400);
                    echo json_encode(["mensaje" => "ID no proporcionado"]);
                }
                break;
            case 'POST':
                if (!empty($data)) {
                    $usuarioController->registrarUsuario($data);
                } else {
                    http_response_code(400);
                    echo json_encode(["mensaje" => "Datos incompletos"]);
                }
                break;
            case 'PUT':
                if (!empty($id) && !empty($data)) {
                    $usuarioController->actualizarUsuario($id, $data);
                } else {
                    http_response_code(400);
                    echo json_encode(["mensaje" => "ID o datos no proporcionados"]);
                }
                break;
            case 'DELETE':
                if (!empty($id)) {
                    $usuarioController->eliminarUsuario($id);
                } else {
                    http_response_code(400);
                    echo json_encode(["mensaje" => "ID no proporcionado"]);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(["mensaje" => "Método no permitido"]);
        }
        break;

    case 'admin':
        $adminController = new AdministradorController($conn);
        switch ($requestMethod) {
            case 'GET':
                if (!empty($id)) {
                    $admin = $adminController->obtenerPorId($id);
                    if ($admin) {
                        http_response_code(200);
                        echo json_encode($admin);
                    } else {
                        http_response_code(404);
                        echo json_encode(["mensaje" => "Administrador no encontrado"]);
                    }
                } else {
                    $admins = $adminController->obtenerTodos();
                    http_response_code(200);
                    echo json_encode($admins);
                }
                break;
            case 'POST':
                if (!empty($data)) {
                    $resultado = $adminController->crear($data);
                    if ($resultado) {
                        http_response_code(201);
                        echo json_encode(["mensaje" => "Administrador creado con éxito"]);
                    } else {
                        http_response_code(500);
                        echo json_encode(["mensaje" => "Error al crear administrador"]);
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(["mensaje" => "Datos incompletos"]);
                }
                break;
            case 'PUT':
                if (!empty($id) && !empty($data)) {
                    $resultado = $adminController->actualizar($id, $data);
                    if ($resultado) {
                        http_response_code(200);
                        echo json_encode(["mensaje" => "Administrador actualizado con éxito"]);
                    } else {
                        http_response_code(404);
                        echo json_encode(["mensaje" => "Administrador no encontrado"]);
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(["mensaje" => "ID o datos no proporcionados"]);
                }
                break;
            case 'DELETE':
                if (!empty($id)) {
                    $resultado = $adminController->eliminar($id);
                    if ($resultado) {
                        http_response_code(200);
                        echo json_encode(["mensaje" => "Administrador eliminado con éxito"]);
                    } else {
                        http_response_code(404);
                        echo json_encode(["mensaje" => "Administrador no encontrado"]);
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(["mensaje" => "ID no proporcionado"]);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(["mensaje" => "Método no permitido"]);
        }
        break;

    case 'carrito':
        $carritoController = new CarritoController($conn);
        switch ($requestMethod) {
            case 'GET':
                if (!empty($id)) {
                    $carrito = $carritoController->obtenerPorId($id);
                    if ($carrito) {
                        http_response_code(200);
                        echo json_encode($carrito);
                    } else {
                        http_response_code(404);
                        echo json_encode(["mensaje" => "Carrito no encontrado"]);
                    }
                } else {
                    $carritos = $carritoController->obtenerTodos();
                    http_response_code(200);
                    echo json_encode($carritos);
                }
                break;
            case 'POST':
                if (!empty($data)) {
                    $resultado = $carritoController->crear($data);
                    if ($resultado) {
                        http_response_code(201);
                        echo json_encode(["mensaje" => "Carrito creado con éxito"]);
                    } else {
                        http_response_code(500);
                        echo json_encode(["mensaje" => "Error al crear carrito"]);
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(["mensaje" => "Datos incompletos"]);
                }
                break;
            case 'PUT':
                if (!empty($id) && !empty($data)) {
                    $resultado = $carritoController->actualizar($id, $data);
                    if ($resultado) {
                        http_response_code(200);
                        echo json_encode(["mensaje" => "Carrito actualizado con éxito"]);
                    } else {
                        http_response_code(404);
                        echo json_encode(["mensaje" => "Carrito no encontrado"]);
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(["mensaje" => "ID o datos no proporcionados"]);
                }
                break;
            case 'DELETE':
                if (!empty($id)) {
                    $resultado = $carritoController->eliminar($id);
                    if ($resultado) {
                        http_response_code(200);
                        echo json_encode(["mensaje" => "Carrito eliminado con éxito"]);
                    } else {
                        http_response_code(404);
                        echo json_encode(["mensaje" => "Carrito no encontrado"]);
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(["mensaje" => "ID no proporcionado"]);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(["mensaje" => "Método no permitido"]);
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(["message" => "Recurso no encontrado"]);
}

$conn->close();
?>