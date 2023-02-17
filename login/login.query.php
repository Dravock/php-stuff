<?php 
require_once(API_ROOT . "/config.php");
require_once(API_ROOT . "/base/db.php");

class UserLogin extends Database{
    
    private $table;

    public function __construct()
    {
        parent::__construct();
        $this->userTable = Config::$userTable;
    }

    public function verifyUser($obj){
        $stmt = $this->conn->prepare("SELECT * From $this->userTable where ma_id='$obj->userId'");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetchAll();
    }
}
?>