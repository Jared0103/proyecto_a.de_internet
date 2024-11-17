<?php
class Carrito {
    public $car_id;
    public $car_total;
    public $car_usu_id;

    public function __construct($car_id, $car_total, $car_usu_id) {
        $this->car_id = $car_id;
        $this->car_total = $car_total;
        $this->car_usu_id = $car_usu_id;
    }
}
?>
