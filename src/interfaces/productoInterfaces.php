<?php
interface ProductoInterface {
    public function guardarProducto($data);
    public function listarProductos();
    public function eliminarProducto($id);
}
?>
