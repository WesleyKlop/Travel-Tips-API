<?php
namespace TravelTips;

/**
 * Created by PhpStorm.
 * User: wesley
 * Date: 9-3-16
 * Time: 20:38
 */
class User implements \JsonSerializable
{
    public $iss;
    public $aud;
    public $sub;
    public $email_verified;
    public $azp;
    public $email;
    public $iat;
    public $exp;
    public $name;
    public $picture;
    public $given_name;
    public $family_name;
    public $locale;
    public $payload;

    /**
     * User constructor.
     * @param $payload
     */
    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public static function fromPayload(array $payload)
    {
        $user = new self($payload);
        $user->iss = $payload['iss'];
        $user->aud = $payload['aud'];
        $user->sub = $payload['sub'];
        $user->email_verified = $payload['email_verified'];
        $user->azp = $payload['azp'];
        $user->email = $payload['email'];
        $user->iat = $payload['iat'];
        $user->exp = $payload['exp'];
        $user->name = $payload['name'];
        $user->picture = $payload['picture'];
        $user->given_name = $payload['given_name'];
        $user->family_name = $payload['family_name'];
        $user->locale = $payload['locale'];

        return $user;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->payload;
    }
}