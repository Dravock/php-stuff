<?php 
require_once("../../base/start.php");
require_once(API_ROOT . "/controllers/postDataTimes/postData.query.php");
require_once(API_ROOT . "/base/jwt.php");

function createOrder () {
    date_default_timezone_set("Germany/Berlin");

    try {
        $post_json  = file_get_contents('php://input');
        $obj = json_decode($post_json);
        $date = date("Y-m-d");
        $time = date("H:i:s");


        $query = new CreateOrder();
        if(!$query->insertTime($obj,$date,$time)) {
            throw new Exception('Something went wrong');
            return;
        }

        if($obj->action === 'start work'){
            if(!$query->insertLastProject($obj)) {
                throw new Exception('Something went wrong');
                return;
            }
            
            if(!$query->insertLastProject($obj)) {
                throw new Exception('Something went wrong');
                return;
            }
        }
        http_response_code(200);

    } catch (Throwable $e) {
        http_response_code(500);
    }
}

$api = new RestApi(null,'createOrder');
?>