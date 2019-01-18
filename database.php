<?php
class Database
{
    private $host = "localhost";

    private $db_name = "apiserver"; 

    private $username = "root";


    private $password = "";


    public $conn;
     
    public function makeConnection()
    {
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Database Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
