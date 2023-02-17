<?php 
require_once("../../base/start.php");
require_once(API_ROOT . "/controllers/login/login.query.php");
require_once(API_ROOT . "/base/jwt.php");

function checkUserLogin(){
    AuthMiddleware::checkAuth();
}

function userLogin () {
    try {
        $post_json  = file_get_contents('php://input');
        $obj = json_decode($post_json);
        
        $query = new UserLogin();
        $result = $query->verifyUser($obj);
        $verified = password_verify($obj->password,$result[0]['password']);
        if($verified){
            $jwt = JWT::generate_jwt(['vorname'=>$result[0]['firstname']]);
            echo json_encode($jwt);
            http_response_code(200);
            return;
        }
        http_response_code(401);
    } catch (Throwable $e) {
        http_response_code(500);
    }
}

$api = new RestApi('checkUserLogin','userLogin');
?>