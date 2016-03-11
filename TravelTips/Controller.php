<?php
/**
 * Created by PhpStorm.
 * User: wesley
 * Date: 9-3-16
 * Time: 21:04
 */

namespace TravelTips;

use PDO;
use RuntimeException;

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

    public function postCountryTip($country, $title, $message)
    {
        if (empty($country)
            || empty($title)
        )
            throw new RuntimeException("Missing parameters");

        $stmt = $this->dbh->prepare(
            'INSERT INTO
              Tips (CountryId, Title, Message)
            VALUES
              (:CountryId, :Title, :Message);'
        );

        $stmt->bindParam(":CountryId", $country);
        $stmt->bindParam(":Title", $title);
        $stmt->bindParam(":Message", $message);

        return $stmt->execute()
            ? "Successfully added tip with ID " . $this->dbh->lastInsertId()
            : "Error executing query";
    }

    public function checkCountryIdExists($id)
    {
        if (empty($id)) {
            throw new RuntimeException("Missing parameter id");
        }
        $stmt = $this->dbh->prepare(
            'SELECT CountryId
            FROM Countries
            WHERE CountryId = :CountryId;'
        );
        $stmt->bindParam(':CountryId', $id);

        $stmt->execute();

        return ($stmt->rowCount() > 0) ? "Country exists" : "Country doesn't exist";
    }
}