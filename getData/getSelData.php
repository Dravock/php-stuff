<?php
require_once("../../base/start.php");
require_once(API_ROOT . "/controllers/getData/getData.query.php");
require_once(API_ROOT . "/base/jwt.php");

function getSelData() {
    try {
        if($_GET['token']){
            $obj = ['token'=>$_GET['token']];
            $jwtInit= new JWT;
            $checkJWT =$jwtInit->is_jwt_valid($obj['token']);
            if($checkJWT){
                $query = new GetData();
                $resultsODATA = $query->getSelectData();
    
                echo json_encode($resultsODATA);
                http_response_code(200);
            }else{
                http_response_code(401);
                die;
            }
        }else{
            http_response_code(406);
            die;
        }
    } catch (Throwable $e) {
        http_response_code(500);
    }
}
$api = new RestApi('getSelData');
?>