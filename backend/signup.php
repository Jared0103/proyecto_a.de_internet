<?php
include_once 'database.php'; // Incluye el archivo database.php

// Crear una instancia de la clase Database
$database = new Database();
$conn = $database->getConnection(); // Obtener la conexión

if ($conn === null) {
    echo json_encode(["error" => "Conexión a la base de datos fallida"]);
    exit();
}

// Obtener los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $correo = $_POST['email'];
    $contrasena = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Verifica si las contraseñas coinciden
    if ($contrasena != $confirmPassword) {
        echo json_encode(["error" => "Las contraseñas no coinciden"]);
        exit();
    }

    // Preparar consulta SQL
    $sql = "INSERT INTO usuario (usu_nombre, usu_correo, usu_contrasena) VALUES (:nombre, :correo, :contrasena)";
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':correo', $correo);
    $stmt->bindParam(':contrasena', password_hash($contrasena, PASSWORD_DEFAULT));

    // Ejecutar consulta
    if ($stmt->execute()) {
        echo json_encode(["success" => "Usuario registrado exitosamente"]);
        header("Location: ../frontend/catalogo.html");  // Redirige a catalogo.html
        exit();
    } else {
        echo json_encode(["error" => "Error al registrar usuario"]);
    }    
}
?>
