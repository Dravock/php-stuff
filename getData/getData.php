<?php
require_once("../../base/start.php");
require_once(API_ROOT . "/controllers/getData/getData.query.php");
require_once(API_ROOT . "/base/jwt.php");


function hoursandmins($time){
    $timetoHoure=gmdate("H:i:s", $time);
    $readableTime=$timetoHoure . 'h';

    return $readableTime;
}


function getWorkTime($obj,$atNr){
    $indexcount = 0;

    foreach ($obj as $singleObj  ){
        foreach ($singleObj as $key=> $value) {
            if($key=== 'daily_Worktime'){
                $getHoursAndMinutes = hoursandmins(intval($value));
            }
            if($key=== 'total_worktime'){
                $getHoursAndMinutes = hoursandmins(intval($value));
            }
        }
        if($atNr==='default'){
            $resultAll[$indexcount]=['atNr'=>$singleObj['atNr'],'pTitle'=>$singleObj['pTitle'],'total_worktime'=>$getHoursAndMinutes,'status'=>$singleObj['status'],'ma_id'=>$singleObj['ma_id'],'username'=>$singleObj['username']];
            $indexcount ++;
        }else{
            $resultAll[$indexcount]=['atNr'=>$singleObj['atNr'],'pTitle'=>$singleObj['pTitle'],'startDate'=>$singleObj['startDate'],"startTime"=>$singleObj['startTime'],'endDate'=>$singleObj['endDate'],'endTime'=>$singleObj['endTime'],'daily_worktime'=>$getHoursAndMinutes,'status'=>$singleObj['status'],'ma_id'=>$singleObj['ma_id'],'username'=>$singleObj['username']];
            $indexcount ++;
            }
        }
    return $resultAll;
}

function getData() {
    try {
        if($_GET['token']){
            $obj = ['usrID'=>$_GET['usrID'],'atNr'=>$_GET['atNr'],'token'=>$_GET['token']];
            $atNr = $obj['atNr'];
            $jwtInit= new JWT;
            $checkJWT =$jwtInit->is_jwt_valid($obj['token']);
            if($checkJWT){
                $query = new GetData();

                if($obj['atNr'] === "default"){
                    $resultsDefault = $query->getDataDefault($obj);
                    $finArr=getWorkTime($resultsDefault,$atNr);
                    echo json_encode($finArr);
                }else{
                    $resultSpecific = $query->getDataSpecific($obj);
                    $singleWorktime=getWorkTime($resultSpecific,$atNr);
                    echo json_encode($singleWorktime);
                }
                http_response_code(200);
            }else{
                throw new Exception('Unauthorized access');
                return ;
            }
        }
    } catch (Throwable $e) {
        http_response_code(500);
    }
}
$api = new RestApi('getData');
?>