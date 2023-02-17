<?php 
require_once(API_ROOT . "/config.php");
require_once(API_ROOT . "/base/db.php");

class GetData extends Database{
    public function __construct()
    {
        parent::__construct();
        $this->wpTable = Config::$wpTable;
        $this->tTable = Config::$timesTable;
        $this->oTable = Config::$orderTable;
        $this->cTable = Config::$customerTable;
        $this->uTable = Config::$userTable;
    }

    public  function getDataSpecific($getObj){
        $userID=$getObj['usrID'];
        $atNr=$getObj['atNr'];

        $stmt = $this->conn->prepare("SELECT    orders.atNr,orders.customerID,orders.pTitle,
                                                times.endDate,times.startDate,times.startTime,times.endTime,TIMESTAMPDIFF(SECOND, times.startTime, times.endTime) as daily_Worktime,
                                                $this->wpTable.status,
                                                $this->uTable.ma_id,$this->uTable.username FROM ((($this->oTable 
                                                        INNER JOIN $this->tTable ON $this->oTable.atNr = $this->tTable.atNr)
                                                        INNER JOIN $this->wpTable ON $this->oTable.atNr = $this->wpTable.atNR)
                                                        INNER JOIN $this->uTable ON  $this->oTable.ma_id = $this->uTable.ma_id)
                                                        WHERE   $this->oTable.atNr='$atNr'
                                                            AND $this->uTable.ma_id='$userID';");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetchAll();
}

    public function getDataDefault(){
        // SELECT SUM(endTime-startTime) FROM times GROUP BY atNr;

        $stmt = $this->conn->prepare("SELECT $this->oTable.atNr,pTitle,SUM(TIMESTAMPDIFF(SECOND, times.startTime, times.endTime)) as total_worktime,$this->wpTable.status,$this->uTable.ma_id,$this->uTable.username
                                        FROM ((( $this->oTable 
                                                INNER JOIN $this->tTable ON $this->oTable.atNr = $this->tTable.atNr)
                                                INNER JOIN $this->wpTable ON $this->oTable.atNr = $this->wpTable.atNR)
                                                INNER JOIN $this->uTable ON  $this->oTable.ma_id = $this->uTable.ma_id)
                                        GROUP BY orders.atNr;");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetchAll();
    }

    public function getSelectData(){
        $stmt = $this->conn->prepare("SELECT pTitle,atNr From $this->oTable  ;");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetchAll();
    }
}
?>