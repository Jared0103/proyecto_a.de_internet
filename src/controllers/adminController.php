<?php
require_once __DIR__ . '/../repositories/AdministradorRepository.php';

class AdministradorController {
    private $administradorRepo;

    public function __construct($conn) {
        $this->administradorRepo = new AdministradorRepository($conn);
    }

    public function obtenerTodos() {
        return $this->administradorRepo->obtenerTodosAdministradores();
    }

    public function obtenerPorId($adm_id) {
        return $this->administradorRepo->obtenerAdministradorPorId($adm_id);
    }

    public function crear($data) {
        $administrador = new Administrador(null, $data['adm_nombre'], $data['adm_correo'], $data['adm_contrasena']);
        return $this->administradorRepo->crearAdministrador($administrador);
    }

    public function actualizar($adm_id, $data) {
        $administrador = new Administrador($adm_id, $data['adm_nombre'], $data['adm_correo'], $data['adm_contrasena']);
        return $this->administradorRepo->actualizarAdministrador($administrador);
    }

    public function eliminar($adm_id) {
        return $this->administradorRepo->eliminarAdministrador($adm_id);
    }
}
?>
