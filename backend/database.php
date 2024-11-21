<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

class Database {
    private $host = 'localhost:8889'; // Puede ser '127.0.0.1' también
    private $db_name = 'aplicacionesproyecto'; // Asegúrate de que esta base de datos exista
    private $username = 'root'; // Verifica que este sea tu usuario de MySQL
    private $password = 'root'; // Verifica que esta sea tu contraseña de MySQL
    private $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            // Cambié a "localhost" en lugar de "localhost:8889" para evitar posibles problemas de puerto
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name;
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo "Conexión exitosa"; // Comentado para evitar mostrarlo en producción
        } catch (PDOException $e) {
            // Muestra el mensaje de error detallado si hay un problema
            echo "Error de conexión: " . $e->getMessage();
        }
        return $this->conn;
    }
}
?>
