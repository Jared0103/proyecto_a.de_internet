<?php
interface UsuarioInterface {
    public function registrarUsuario($data);
    public function obtenerUsuarioPorCorreo($correo);
    public function actualizarUsuario($id, $data);
    public function eliminarUsuario($id);
}
?>
