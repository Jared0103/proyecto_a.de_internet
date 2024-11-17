<?php
interface CarritoInterface {
    public function obtenerCarritos();
    public function obtenerCarritoPorId($car_id);
    public function crearCarrito($carrito);
    public function actualizarCarrito($carrito);
    public function eliminarCarrito($car_id);
    public function obtenerDetallesCarrito($car_id);
}
?>
