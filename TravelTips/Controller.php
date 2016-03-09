<?php
/**
 * Created by PhpStorm.
 * User: wesley
 * Date: 9-3-16
 * Time: 21:04
 */

namespace TravelTips;

/**
 * Class Controller
 * @package TravelTips
 */
class Controller
{
    private $dbh;
    private $user;
    private $gClient;

    /**
     * Controller constructor.
     * @param User $user
     * @param \Google_Client $gClient
     */
    public function __construct(User $user, \Google_Client $gClient)
    {
        $this->dbh = Database::getInstance();
        $this->user = $user;
        $this->gClient = $gClient;
    }


}