<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json'); // Asegúrate de que el contenido sea JSON

// Verificar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Comprobar que los datos han llegado correctamente
    if (!isset($_POST['usuario']) || !isset($_POST['contrasena'])) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos de usuario o contraseña']);
        exit();
    }

    // Obtener los datos enviados por POST
    $usuario = $_POST['usuario'];   // Correo del usuario
    $contrasena = $_POST['contrasena'];   // Contraseña del usuario

    // Conexión a la base de datos
    $servername = "localhost:8889"; // Cambia si tu servidor es diferente
    $username = "root"; // Usuario de MySQL
    $password = "root"; // Contraseña de MySQL
    $dbname = "aplicacionesproyecto"; // Nombre de tu base de datos

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Consulta SQL para verificar el correo en la tabla de administrador
    $queryAdmin = "SELECT * FROM administrador WHERE adm_correo = ?";
    $stmtAdmin = $conn->prepare($queryAdmin);
    $stmtAdmin->bind_param("s", $usuario);  // 's' para string, buscando el correo
    $stmtAdmin->execute();
    $resultAdmin = $stmtAdmin->get_result();

    // Si el correo está en la tabla administrador
    if ($resultAdmin->num_rows > 0) {
        $admin = $resultAdmin->fetch_assoc();
        
        // Verificar la contraseña (si es correcto)
        if ($admin['adm_contrasena'] === $contrasena) {
            // Contraseña correcta para administrador
            echo json_encode(['success' => true, 'message' => 'Inicio de sesión exitoso']);
            header("Location: ../frontend/inventario.html");  // Redirigir al inventario
            exit();
        } else {
            // Contraseña incorrecta
            echo json_encode(['success' => false, 'message' => 'Contraseña incorrecta para administrador']);
        }
    } else {
        // Si no es administrador, verificar en la tabla de usuario
        $queryUser = "SELECT * FROM usuario WHERE usu_correo = ?";
        $stmtUser = $conn->prepare($queryUser);
        $stmtUser->bind_param("s", $usuario);  // 's' para string, buscando el correo
        $stmtUser->execute();
        $resultUser = $stmtUser->get_result();

        // Si el correo está en la tabla usuario
        if ($resultUser->num_rows > 0) {
            $user = $resultUser->fetch_assoc();
            
            // Verificar la contraseña (si es correcto)
            if ($user['usu_contrasena'] === $contrasena) {
                // Contraseña correcta para usuario
                echo json_encode(['success' => true, 'message' => 'Inicio de sesión exitoso']);
                header("Location: ../frontend/catalogo.html");  // Redirigir al catálogo
                exit();
            } else {
                // Contraseña incorrecta
                echo json_encode(['success' => false, 'message' => 'Contraseña incorrecta para usuario']);
            }
        } else {
            // El usuario no existe en ninguna de las dos tablas
            echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
        }
    }

    // Cerrar la conexión
    $stmtAdmin->close();
    $stmtUser->close();
    $conn->close();
} else {
    // Si no es un POST, devuelve un error 405
    http_response_code(405);  // Código HTTP 405: Método no permitido
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>
