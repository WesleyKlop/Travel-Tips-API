<?php
/**
 * Created by PhpStorm.
 * User: wesley
 * Date: 9-3-16
 * Time: 18:20
 */
require_once "vendor/autoload.php";

use TravelTips\Helper;
use TravelTips\User;

session_start();

// Handler that converts errors to JSON objects so the Java client can do something with it
set_exception_handler(Helper::getExceptionHandler());

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
} else {
    throw new \TravelTips\AuthenticationException();
}

echo json_encode($response);