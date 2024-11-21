<?php
require_once '../repositories/ProductoRepository.php';

class ProductoController {
    private $productoRepository;

    public function __construct() {
        $this->productoRepository = new ProductoRepository();
    }

    public function crearProducto($data) {
        $this->productoRepository->guardarProducto($data);
    }

    public function obtenerProductos() {
        return $this->productoRepository->listarProductos();
    }

    public function eliminarProducto($id) {
        $this->productoRepository->eliminarProducto($id);
    }

    // Puedes agregar más métodos según lo que necesites.
}
?>
