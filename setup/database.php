<?php

/*
 * This is a basic class to connect to MySQL
 * database
 */
class Database{
 
    // specify your own database credentials
    private $host = "localhost";
    private $db_name = "arbel";
    private $username = "arbel_admin";
    private $password = "arbel_admin";
    public $conn;
 
    // get the database connection
    public function getConnection(){
 
        $this->conn = null;
 
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
 
        return $this->conn;
    }
}