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
        $stmt = $this->dbh->prepare(
            "SELECT
              Countries.CountryId,
              Countries.Name,
              (SELECT COUNT(*)
               FROM Tips
               WHERE Tips.CountryId = Countries.CountryId) AS TipsCount
            FROM Countries
            ORDER BY TipsCount DESC, Countries.Name ASC;"

        );
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCountriesFiltered($name)
    {
        $query = '%' . $name . '%';

        $stmt = $this->dbh->prepare(
            'SELECT
              Countries.CountryId,
              Countries.Name,
              (SELECT COUNT(*)
               FROM Tips
               WHERE Tips.CountryId = Countries.CountryId) AS TipsCount
            FROM Countries
            WHERE Countries.Name LIKE :CountryQuery
            ORDER BY TipsCount DESC, Countries.Name ASC;'
        );
        $stmt->bindParam(":CountryQuery", $query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCountryTips($country)
    {
        $stmt = $this->dbh->prepare(
            'SELECT
                  TipId,
                  Title,
                  Message
                FROM Tips
                WHERE CountryId = :CountryId;'
        );

        $stmt->bindParam(':CountryId', $country);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}