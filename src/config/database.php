<?php
class Database {
    private static $dbConnection = null;

    public static function getConnection() {
        if (self::$dbConnection == null) {
            try {
                self::$dbConnection = new PDO('mysql:host=localhost;dbname=APLICACIONESPROYECTO', 'root', ''); 
                self::$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }
        return self::$dbConnection;
    }
}
?>
