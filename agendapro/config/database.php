<?php
// config/database.php
class Database {
    private $host = 'localhost';
    private $db_name = 'mascapa2_agendapro_db';
    private $username = 'mascapa2_agenda';
    private $password = '@.JsmR_0613.#';
    public $conn;


    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
                )
            );
        } catch(PDOException $exception) {
            error_log("Error de conexion: " . $exception->getMessage());
            die("Error de conexion a la base de datos.");
        }
        return $this->conn;
    }

}
?>