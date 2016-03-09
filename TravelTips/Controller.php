<?php
/**
 * Created by PhpStorm.
 * User: wesley
 * Date: 9-3-16
 * Time: 21:04
 */

namespace TravelTips;
use PDO;

/**
 * Class Controller
 * @package TravelTips
 */
class Controller
{
    private $dbh;
    private $gClient;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->dbh = Database::getInstance();
    }

    /**
     * @param mixed $gClient
     * @return Controller
     */
    public function setGClient($gClient)
    {
        $this->gClient = $gClient;
        return $this;
    }

    public function getAllCountries()
    {
        $stmt = $this->dbh
            ->prepare('SELECT Countries.CountryId,
                        Countries.Name,
                        (SELECT COUNT(Tips.CountryId)
                            FROM Tips
                            WHERE Tips.TipId = CountryId) AS TipsCount
                        FROM Countries;');
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCountriesFiltered($name)
    {
        if (!empty($name)) {
            $query = '%' . $name . '%';
        }

        $stmt = $this->dbh
            ->prepare('SELECT Countries.CountryId,
                        Countries.Name,
                        (SELECT COUNT(Tips.CountryId)
                            FROM Tips
                            WHERE Tips.TipId = CountryId) AS TipsCount
                        FROM Countries
                        WHERE Countries.Name LIKE :CountryQuery');
        $stmt->bindParam(":CountryQuery", $query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}