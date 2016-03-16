<?php
/**
 * Created by PhpStorm.
 * User: wesley
 * Date: 9-3-16
 * Time: 21:56
 */

namespace TravelTips;


class Helper
{
    function __construct()
    {
    }

    public static function getAction()
    {
        if (isset($_GET['action'])
            && !empty($_GET['action'])
        ) {
            return $_GET['action'];
        }
        return false;
    }

    public static function postAction()
    {
        if (isset($_POST['action'])
            && !empty($_POST['action'])
        ) {
            return $_POST['action'];
        }
        return false;
    }

    /**
     * Returns a callable that prints the exception in the JSONObject format
     *
     * @return \Callable JSON exception handler
     */
    public static function getExceptionHandler()
    {
        /** @param \Exception|AuthenticationException $e */
        return function ($e) {
            $response = [
                "status" => "failure",
                "type" => get_class($e),
                "response" => $e->getMessage(),
                "session" => $_SESSION,
                "session_started" => session_status() == PHP_SESSION_ACTIVE
            ];
            die(json_encode($response));
        };
    }

    /**
     * Checks if the user is authenticated by verifying
     * 1. The variable $_SESSION['user'] is set
     * 2. The variable $_SESSION['user'] is not empty
     * 3. The variable $_SESSION['user'] is an instance of User
     *
     * @return bool true if the user is authenticated
     */
    public static function isUserAuthenticated()
    {
        return (isset($_SESSION['user'])
            && !empty($_SESSION['user'])
            && $_SESSION['user'] instanceof User);
    }

    function __clone()
    {
    }
}