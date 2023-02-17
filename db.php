<?php
require_once(API_ROOT . "/config.php");

class Database
{
    protected $conn; 

    protected function __construct()
    {
        try {
            $servername = Config::$host;
            $dbname = Config::$dbname;
            $this->conn = new PDO("mysql:host=$servername;dbname=$dbname", Config::$username, Config::$password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // set the PDO error mode to exception
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
}