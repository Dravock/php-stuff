<?php
require_once("../../base/start.php");

AuthMiddleware::checkAuth();
http_response_code(200);