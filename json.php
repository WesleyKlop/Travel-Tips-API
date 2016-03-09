<?php
/**
 * Created by PhpStorm.
 * User: wesley
 * Date: 9-3-16
 * Time: 21:55
 */
use TravelTips\Controller;
use TravelTips\Helper;

require_once "vendor/autoload.php";

session_start();
header('Content-Type: application/json');
set_exception_handler(Helper::getExceptionHandler());

$response = null;
$status = "failure";
$data = [
    "status" => &$status,
    "response" => &$response
];
$controller = new Controller();

if ($_SERVER['REQUEST_METHOD'] === "GET") {

    switch (Helper::getAction()) {
        case "countries":
            if (isset($_GET['name'])) {
                $response = $controller->getCountriesFiltered($_GET['name']);
                $status = "success";
            } else {
                $response = $controller->getAllCountries();
                $status = "success";
            }
            break;
        default:
            $response = "No action submitted";
            break;
    }

} elseif ($_SERVER['REQUEST_METHOD'] === "POST") {

    // TODO: Create actions for POST

}

$json = json_encode($data);

if ($json === false) {
    $json = json_encode([
        "status" => "failure",
        "response" => "There was an error encoding the data to JSON"
    ]);
    if ($json === false) {
        // THIS SHOULD NEVER HAPPEN!? WTF!?
        $json = '{"status": "failure", "response": "You should really now see this, please report how you did this!"}';
    }
}

echo $json;