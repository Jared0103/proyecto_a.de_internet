<?php
class Usuario {
    public $usu_id;
    public $usu_nombre;
    public $usu_correo;
    public $usu_contrasena;

    public function __construct($usu_id, $usu_nombre, $usu_correo, $usu_contrasena) {
        $this->usu_id = $usu_id;
        $this->usu_nombre = $usu_nombre;
        $this->usu_correo = $usu_correo;
        $this->usu_contrasena = $usu_contrasena;
    }
}
?>
