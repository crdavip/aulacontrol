<?php
class ConnPDO
{
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "aulacontrol";
    private $pdo;

    public function __construct()
    {
        try {
            $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->database}", $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $ex) {
            echo "Error de conexión: " . $ex->getMessage();
        }
    }

    public function getConn()
    {
        return $this->pdo;
    }

    public function closeConn()
    {
        $this->pdo = null;
    }
}
?>