<?php
class Administrador {
    public $adm_id;
    public $adm_nombre;
    public $adm_correo;
    public $adm_contrasena;

    public function __construct($adm_id, $adm_nombre, $adm_correo, $adm_contrasena) {
        $this->adm_id = $adm_id;
        $this->adm_nombre = $adm_nombre;
        $this->adm_correo = $adm_correo;
        $this->adm_contrasena = $adm_contrasena;
    }
}
?>
