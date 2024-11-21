<?php
require_once '../repositories/UsuarioRepository.php';

class UsuarioController {
    private $usuarioRepository;

    public function __construct() {
        $this->usuarioRepository = new UsuarioRepository();
    }

    public function registrarUsuario($data) {
        $mensaje = $this->usuarioRepository->registrarUsuario($data);
        echo json_encode(["mensaje" => $mensaje]);
    }

    public function obtenerUsuario($correo) {
        $usuario = $this->usuarioRepository->obtenerUsuarioPorCorreo($correo);
        if ($usuario) {
            echo json_encode($usuario);
        } else {
            echo json_encode(["mensaje" => "Usuario no encontrado."]);
        }
    }

    public function actualizarUsuario($id, $data) {
        $mensaje = $this->usuarioRepository->actualizarUsuario($id, $data);
        echo json_encode(["mensaje" => $mensaje]);
    }

    public function eliminarUsuario($id) {
        $mensaje = $this->usuarioRepository->eliminarUsuario($id);
        echo json_encode(["mensaje" => $mensaje]);
    }
}
?>
