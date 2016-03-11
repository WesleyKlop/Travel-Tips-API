<?php
/**
 * Created by PhpStorm.
 * User: wesley
 * Date: 11-3-16
 * Time: 23:46
 */

namespace TravelTips;

/**
 * Class AuthenticationException
 * @package TravelTips
 */
class AuthenticationException extends \Exception
{
    protected $message = "Failed to authenticate user!";
}