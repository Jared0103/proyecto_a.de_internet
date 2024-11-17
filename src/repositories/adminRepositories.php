<?php
require_once __DIR__ . '/../interfaces/AdministradorInterface.php';
require_once __DIR__ . '/../models/Administrador.php';

class AdministradorRepository implements AdministradorInterface {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function obtenerTodosAdministradores() {
        $stmt = $this->conn->query("SELECT * FROM administrador");
        $administradores = [];
        while ($row = $stmt->fetch_assoc()) {
            $administradores[] = new Administrador($row['adm_id'], $row['adm_nombre'], $row['adm_correo'], $row['adm_contrasena']);
        }
        return $administradores;
    }

    public function obtenerAdministradorPorId($adm_id) {
        $stmt = $this->conn->prepare("SELECT * FROM administrador WHERE adm_id = ?");
        $stmt->bind_param("i", $adm_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return new Administrador($row['adm_id'], $row['adm_nombre'], $row['adm_correo'], $row['adm_contrasena']);
    }

    public function crearAdministrador($administrador) {
        $stmt = $this->conn->prepare("INSERT INTO administrador (adm_nombre, adm_correo, adm_contrasena) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $administrador->adm_nombre, $administrador->adm_correo, $administrador->adm_contrasena);
        return $stmt->execute();
    }

    public function actualizarAdministrador($administrador) {
        $stmt = $this->conn->prepare("UPDATE administrador SET adm_nombre = ?, adm_correo = ?, adm_contrasena = ? WHERE adm_id = ?");
        $stmt->bind_param("sssi", $administrador->adm_nombre, $administrador->adm_correo, $administrador->adm_contrasena, $administrador->adm_id);
        return $stmt->execute();
    }

    public function eliminarAdministrador($adm_id) {
        $stmt = $this->conn->prepare("DELETE FROM administrador WHERE adm_id = ?");
        $stmt->bind_param("i", $adm_id);
        return $stmt->execute();
    }
}
?>
