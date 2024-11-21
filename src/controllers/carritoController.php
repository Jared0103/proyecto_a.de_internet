<?php
require_once __DIR__ . '/../repositories/CarritoRepository.php';

class CarritoController {
    private $carritoRepo;

    public function __construct($conn) {
        $this->carritoRepo = new CarritoRepository($conn);
    }

    public function obtenerTodos() {
        return $this->carritoRepo->obtenerCarritos();
    }

    public function obtenerPorId($car_id) {
        return $this->carritoRepo->obtenerCarritoPorId($car_id);
    }

    public function crear($data) {
        $carrito = new Carrito(null, $data['car_total'], $data['car_usu_id']);
        return $this->carritoRepo->crearCarrito($carrito);
    }

    public function actualizar($car_id, $data) {
        $carrito = new Carrito($car_id, $data['car_total'], $data['car_usu_id']);
        return $this->carritoRepo->actualizarCarrito($carrito);
    }

    public function eliminar($car_id) {
        return $this->carritoRepo->eliminarCarrito($car_id);
    }

    public function obtenerDetalles($car_id) {
        return $this->carritoRepo->obtenerDetallesCarrito($car_id);
    }
}
?>
