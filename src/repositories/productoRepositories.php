<?php
require_once '../models/Producto.php';
require_once '../config/Database.php';

class ProductoRepository implements ProductoInterface {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    public function guardarProducto($data) {
        $sql = "INSERT INTO producto (pro_nombre, pro_descripcion, pro_precio) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$data['nombre'], $data['descripcion'], $data['precio']]);
    }

    public function listarProductos() {
        $sql = "SELECT * FROM producto";
        $stmt = $this->conn->query($sql);
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $productos;
    }

    public function eliminarProducto($id) {
        $sql = "DELETE FROM producto WHERE pro_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
    }
}
?>
