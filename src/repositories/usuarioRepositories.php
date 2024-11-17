<?php
require_once '../models/Usuario.php';
require_once '../config/Database.php';

class UsuarioRepository implements UsuarioInterface {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    public function registrarUsuario($data) {
        // Verificar si el correo ya está registrado
        $sql = "SELECT * FROM usuario WHERE usu_correo = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$data['usu_correo']]);
        if ($stmt->rowCount() > 0) {
            return "El correo ya está registrado.";
        }

        // Insertar el nuevo usuario
        $sql = "INSERT INTO usuario (usu_nombre, usu_correo, usu_contrasena) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$data['usu_nombre'], $data['usu_correo'], password_hash($data['usu_contrasena'], PASSWORD_BCRYPT)]);
        return "Usuario registrado con éxito.";
    }

    public function obtenerUsuarioPorCorreo($correo) {
        $sql = "SELECT * FROM usuario WHERE usu_correo = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$correo]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($usuario) {
            return new Usuario($usuario['usu_id'], $usuario['usu_nombre'], $usuario['usu_correo'], $usuario['usu_contrasena']);
        }
        return null;
    }

    public function actualizarUsuario($id, $data) {
        $sql = "UPDATE usuario SET usu_nombre = ?, usu_correo = ?, usu_contrasena = ? WHERE usu_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$data['usu_nombre'], $data['usu_correo'], password_hash($data['usu_contrasena'], PASSWORD_BCRYPT), $id]);
        return "Usuario actualizado con éxito.";
    }

    public function eliminarUsuario($id) {
        $sql = "DELETE FROM usuario WHERE usu_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return "Usuario eliminado con éxito.";
    }
}
?>
