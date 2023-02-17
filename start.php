<?php

// ENABLE CORS -> maybe comment out on production
header("Access-Control-Allow-Origin: * ");
header("Access-Control-Allow-Methods: HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
if ($method == "OPTIONS") {
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
    header("HTTP/1.1 200 OK");
    die();
}

// SHOW ERRORS -> comment out on production
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// ===========================================================
define("ROOT", dirname(dirname(dirname(__FILE__))));
define("CSV_ROOT", dirname(dirname(__FILE__)));
define("API_ROOT", dirname(dirname(__FILE__)));


require_once(API_ROOT . "/base/jwt.php");

class AuthMiddleware
{
    public static function checkAuth()
    {
        $arr = getallheaders();
        if(!isset($arr["Authorization"])){
            http_response_code(401);
            die();
        }

        $jwt = trim(explode(' ',$arr["Authorization"])[1]);
        $payload = JWT::is_jwt_valid($jwt);
        return $payload;
    }
}


class RestApi{
    public function __construct($get = null, $post = null, $put = null, $delete = null)
    {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $get ? $get() : $this->notAvailable();
                break;
            case 'POST':
                $post ? $post() : $this->notAvailable();
                break;
            case 'PUT':
                $put ? $put() : $this->notAvailable();
                break;
            case 'DELETE':
                $delete ? $delete() : $this->notAvailable();
                break;
            default:
                http_response_code(404);
                die();
                break;
            }
    }

    private function notAvailable()
    {
        http_response_code(404);
        die();
    }
}

