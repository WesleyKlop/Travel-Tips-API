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
use TravelTips\Helper;
use TravelTips\User;

session_start();

// Handler that converts errors to JSON objects so the Java client can do something with it
set_exception_handler(Helper::getExceptionHandler());

$log = new Logger('Auth');
$log->pushHandler(new StreamHandler($_SERVER['DOCUMENT_ROOT'] . '/logs/auth.log', Logger::DEBUG));

$gClient = new Google_Client();
$gClient->setAuthConfigFile('client_secret.json');

$response = [];

if (isset($_POST['token'])
    && !empty($_POST['token'])
    && ($payload = $gClient->verifyIdToken($_POST['token']))
) {
    $user = User::fromPayload($payload);
    $response["status"] = "success";
    $response["response"] = $user;

    $_SESSION['user'] = $user;
    $log->addDebug("Successfully authenticated user with Email " . $user->email);
} else {
    $log->addDebug("Failed to authenticate user with IP " . $_SERVER['REMOTE_ADDR']);
    $response["status"] = "failure";
    $response["type"] = "AuthFailure";
    $response["response"] = "Failed to verify token";
}

echo json_encode($response);