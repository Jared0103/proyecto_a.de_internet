<?php
interface AdministradorInterface {
    public function obtenerTodosAdministradores();
    public function obtenerAdministradorPorId($adm_id);
    public function crearAdministrador($administrador);
    public function actualizarAdministrador($administrador);
    public function eliminarAdministrador($adm_id);
}
?>
