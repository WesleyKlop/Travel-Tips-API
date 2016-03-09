<?php
/**
 * Created by PhpStorm.
 * User: wesley
 * Date: 9-3-16
 * Time: 18:20
 */
require_once "vendor/autoload.php";

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use TravelTips\User;

session_start();

// Handler that converts errors to JSON objects so the Java client can do something with it
set_exception_handler(function (Exception $e) {
    echo json_encode([
        "status" => "failure",
        "type" => "Exception",
        "response" => $e->getMessage()
    ]);
    die;
});

$log = new Logger('Auth');
$log->pushHandler(new StreamHandler($_SERVER['DOCUMENT_ROOT'] . '/logs/auth.log', Logger::DEBUG));

$gClient = new Google_Client();
$gClient->setAuthConfigFile('client_secret.json');

$response = [];

if (($payload = $gClient->verifyIdToken($_POST['token']))) {
    $response["status"] = "success";
    $response["response"] = $payload;
    $user = User::fromPayload($payload);
    $_SESSION['user'] = $user;
    $_SESSION['isAuthenticated'] = true;
} else {
    header("HTTP/1.0 403 Forbidden");
    $response["status"] = "failure";
    $response["type"] = "AuthFailure";
    $response["response"] = "Failed to verify token";
}

echo json_encode($response);