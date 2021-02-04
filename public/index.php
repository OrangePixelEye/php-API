<?php
require "../bootstrap.php";
use Src\Controller\PeopleController;
use Src\Controller\AccountsController;
use Src\Controller\TransferenceController;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

$userId = null;
if (isset($uri[2])) {
    $userId = (int) $uri[2];
}

$requestMethod = $_SERVER["REQUEST_METHOD"];

//switch case here

switch ($uri[1]) {
    case "people":
        //pass the request method and user ID to the PeopleController and process the HTTP request:
        $controller = new \Src\Controller\PeopleController($dbConnection, $requestMethod, $userId);
        break;
    case "accounts":
        $controller = new \Src\Controller\AccountsController($dbConnection, $requestMethod, $userId);
        break;
    case "transference":
        $controller = new \Src\Controller\TransferenceController($dbConnection, $requestMethod, $userId);
        break;
    default:
        header("HTTP/1.1 404 Not Found");
        exit();
}


$controller->processRequest();