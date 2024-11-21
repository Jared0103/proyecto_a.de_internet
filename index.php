<?php
// Configuración de cabeceras para permitir CORS y JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Configuración de la conexión a la base de datos
$host = 'localhost';
$dbname = 'APLICACIONESPROYECTO';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener la URL y el método HTTP
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Separar la URL en segmentos
$segments = explode('/', trim($requestUri, '/'));

// Determinar el controlador a partir del primer segmento de la URL
$controller = $segments[1] ?? '';
$id = $segments[2] ?? null; // Opcional, si hay un ID en la URL

// Cargar archivos de controladores
require_once __DIR__ . '/src/controllers/ProductoController.php';
require_once __DIR__ . '/src/controllers/UsuarioController.php';
require_once __DIR__ . '/src/controllers/AdminController.php';
require_once __DIR__ . '/src/controllers/CarritoController.php';

// Manejar la solicitud en función del controlador y método
switch ($controller) {
    case 'producto':
        $productoController = new ProductoController();
        switch ($requestMethod) {
            case 'GET':
                echo json_encode($id ? $productoController->obtenerProducto($id) : $productoController->obtenerProductos());
                break;
            case 'POST':
                $data = json_decode(file_get_contents('php://input'), true);
                $productoController->crearProducto($data);
                break;
            case 'PUT':
                if ($id) {
                    $data = json_decode(file_get_contents('php://input'), true);
                    $productoController->actualizarProducto($id, $data);
                }
                break;
            case 'DELETE':
                if ($id) $productoController->eliminarProducto($id);
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
                echo json_encode($id ? $usuarioController->obtenerUsuario($id) : $usuarioController->obtenerUsuarios());
                break;
            case 'POST':
                $data = json_decode(file_get_contents('php://input'), true);
                $usuarioController->registrarUsuario($data);
                break;
            case 'PUT':
                if ($id) {
                    $data = json_decode(file_get_contents('php://input'), true);
                    $usuarioController->actualizarUsuario($id, $data);
                }
                break;
            case 'DELETE':
                if ($id) $usuarioController->eliminarUsuario($id);
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
        }
        break;

    case 'admin':
        $adminController = new AdminController();
        switch ($requestMethod) {
            case 'GET':
                echo json_encode($id ? $adminController->obtenerAdmin($id) : $adminController->obtenerAdmins());
                break;
            case 'POST':
                $data = json_decode(file_get_contents('php://input'), true);
                $adminController->crearAdmin($data);
                break;
            case 'PUT':
                if ($id) {
                    $data = json_decode(file_get_contents('php://input'), true);
                    $adminController->actualizarAdmin($id, $data);
                }
                break;
            case 'DELETE':
                if ($id) $adminController->eliminarAdmin($id);
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
        }
        break;

    case 'carrito':
        $carritoController = new CarritoController();
        switch ($requestMethod) {
            case 'GET':
                echo json_encode($id ? $carritoController->obtenerCarrito($id) : $carritoController->obtenerCarritos());
                break;
            case 'POST':
                $data = json_decode(file_get_contents('php://input'), true);
                $carritoController->crearCarrito($data);
                break;
            case 'PUT':
                if ($id) {
                    $data = json_decode(file_get_contents('php://input'), true);
                    $carritoController->actualizarCarrito($id, $data);
                }
                break;
            case 'DELETE':
                if ($id) $carritoController->eliminarCarrito($id);
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(["message" => "Recurso no encontrado"]);
}

// Cerrar la conexión a la base de datos
$conn->close();
?>