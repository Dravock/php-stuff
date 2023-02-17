<?php 
require_once(API_ROOT . "/config.php");
require_once(API_ROOT . "/base/db.php");

class CreateOrder extends Database{
    
    private $table;

    public function __construct()
    {
        parent::__construct();
        $this->wpTable = Config::$wpTable;
        $this->tTable = Config::$timesTable;
        $this->oTable = Config::$orderTable;
        $this->cTable = Config::$customerTable;
        $this->uTable = Config::$userTable;
    }

    public function checkIfOpenTimeExist($atNr,$date){
        $stmt=$this->conn->prepare("SELECT startDate FROM $this->tTable WHERE atNr = '$atNr' AND startDate='$date'");
        $stmt->execute();
        return $stmt->rowCount();
    }
    public function getHighestTimeId(){
        $stmt=$this->conn->prepare("SELECT MAX(id) as id FROM $this->tTable ");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetchAll();
    }

    public function insertTime($obj,$date,$time){
        if($obj->action === 'start work'){
                $stmt = $this->conn->prepare("INSERT INTO $this->tTable (`atNr`, `startDate`, `startTime`)  VALUES ('$obj->atNr','$date','$time') ");
                return $stmt->execute();
            }
        elseif($obj->action==='finish work'){
            $getHighestId=$this->getHighestTimeId();
            $highestId=$getHighestId[0]['id'];
            $stmt = $this->conn->prepare("UPDATE $this->tTable SET `endDate`='$date',`endTime`='$time',lastcomment='$obj->comment' WHERE atNr = '$obj->atNr' AND startDate='$date' AND id = '$highestId' AND endDate='0000-00-00'");
            return $stmt->execute();
            }
}

    public function insertStatus($obj){
                $stmt = $this->conn->prepare("UPDATE $this->wpTable SET status='$obj->status' WHERE atNr = '$obj->atNr'");
                return $stmt->execute();
            }

    public function insertLastProject($obj){
                $stmt = $this->conn->prepare("UPDATE $this->uTable SET last_project='$obj->atNr' WHERE ma_id ='$obj->user'");
                return $stmt->execute();
            }
}



// $stmt->bindParam(':w',$obj->w);
// $stmt->bindParam(':w',$obj->w);
// $stmt->bindParam(':w',$obj->w);
// $stmt->bindParam(':w',$obj->w);
// $stmt->bindParam(':w',$obj->w);
// $stmt->bindParam(':w',$obj->w);
?>