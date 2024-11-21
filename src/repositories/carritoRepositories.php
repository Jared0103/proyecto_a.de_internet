<?php
require_once __DIR__ . '/../interfaces/CarritoInterface.php';
require_once __DIR__ . '/../models/Carrito.php';

class CarritoRepository implements CarritoInterface {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function obtenerCarritos() {
        $stmt = $this->conn->query("SELECT * FROM carrito");
        $carritos = [];
        while ($row = $stmt->fetch_assoc()) {
            $carritos[] = new Carrito($row['car_id'], $row['car_total'], $row['car_usu_id']);
        }
        return $carritos;
    }

    public function obtenerCarritoPorId($car_id) {
        $stmt = $this->conn->prepare("SELECT * FROM carrito WHERE car_id = ?");
        $stmt->bind_param("i", $car_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return new Carrito($row['car_id'], $row['car_total'], $row['car_usu_id']);
    }

    public function crearCarrito($carrito) {
        $stmt = $this->conn->prepare("INSERT INTO carrito (car_total, car_usu_id) VALUES (?, ?)");
        $stmt->bind_param("di", $carrito->car_total, $carrito->car_usu_id);
        return $stmt->execute();
    }

    public function actualizarCarrito($carrito) {
        $stmt = $this->conn->prepare("UPDATE carrito SET car_total = ?, car_usu_id = ? WHERE car_id = ?");
        $stmt->bind_param("dii", $carrito->car_total, $carrito->car_usu_id, $carrito->car_id);
        return $stmt->execute();
    }

    public function eliminarCarrito($car_id) {
        $stmt = $this->conn->prepare("DELETE FROM carrito WHERE car_id = ?");
        $stmt->bind_param("i", $car_id);
        return $stmt->execute();
    }

    public function obtenerDetallesCarrito($car_id) {
        $stmt = $this->conn->prepare("SELECT * FROM detallecarrito WHERE det_car_id = ?");
        $stmt->bind_param("i", $car_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $detalles = [];
        while ($row = $result->fetch_assoc()) {
            $detalles[] = $row;
        }
        return $detalles;
    }
}
?>
