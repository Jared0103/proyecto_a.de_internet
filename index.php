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
require_once __DIR__ . './src/controllers/productoController.php';
require_once __DIR__ . './src/controllers/usuarioController.php';
require_once __DIR__ . './src/controllers/adminController.php';
require_once __DIR__ . './src/controllers/carritoController.php';

// Manejar la solicitud en función del controlador y método
switch ($controller) {
    case 'producto':
        $productoController = new ProductoController($conn);
        switch ($requestMethod) {
            case 'GET':
                $productoController->obternerProductos();
                break;
            case 'POST':
                $productoController->crearProducto($data);
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
        $usuarioController = new UsuarioController($conn);
        switch ($requestMethod) {
            case 'GET':
                echo $id ? $usuarioController->getUsuario($id) : $usuarioController->getAllUsuarios();
                break;
            case 'POST':
                $usuarioController->createUsuario();
                break;
            case 'PUT':
                if ($id) $usuarioController->updateUsuario($id);
                break;
            case 'DELETE':
                if ($id) $usuarioController->deleteUsuario($id);
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
        }
        break;

    case 'admin':
        $adminController = new AdminController($conn);
        switch ($requestMethod) {
            case 'GET':
                echo $id ? $adminController->getAdmin($id) : $adminController->getAllAdmins();
                break;
            case 'POST':
                $adminController->createAdmin();
                break;
            case 'PUT':
                if ($id) $adminController->updateAdmin($id);
                break;
            case 'DELETE':
                if ($id) $adminController->deleteAdmin($id);
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido"]);
        }
        break;

    case 'carrito':
        $carritoController = new CarritoController($conn);
        switch ($requestMethod) {
            case 'GET':
                echo $id ? $carritoController->getCarrito($id) : $carritoController->getAllCarritos();
                break;
            case 'POST':
                $carritoController->createCarrito();
                break;
            case 'PUT':
                if ($id) $carritoController->updateCarrito($id);
                break;
            case 'DELETE':
                if ($id) $carritoController->deleteCarrito($id);
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
