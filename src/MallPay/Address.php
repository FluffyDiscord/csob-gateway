<?php declare(strict_types=1);

namespace SlevomatCsobGateway\MallPay;

use SlevomatCsobGateway\Encodable;
use SlevomatCsobGateway\Validator;
use function array_filter;

class Address implements Encodable
{

    /**
     * @var string|null
     */
    private $name;
    /**
     * @var string
     */
    private $country;
    /**
     * @var string
     */
    private $city;
    /**
     * @var string
     */
    private $streetAddress;
    /**
     * @var string|null
     */
    private $streetNumber;
    /**
     * @var string
     */
    private $zip;
    /**
     * @var string
     */
    private $addressType;
    public const NAME_LENGTH_MAX = 40;
    public const CITY_LENGTH_MAX = 50;
    public const STREET_ADDRESS_LENGTH_MAX = 100;
    public const STREET_NUMBER_LENGTH_MAX = 25;
    public const ZIP_LENGTH_MAX = 10;

    public function __construct(?string $name, string $country, string $city, string $streetAddress, ?string $streetNumber, string $zip, string $addressType)
    {
        $this->name = $name;
        $this->country = $country;
        $this->city = $city;
        $this->streetAddress = $streetAddress;
        $this->streetNumber = $streetNumber;
        $this->zip = $zip;
        $this->addressType = $addressType;
        Validator::checkWhitespacesAndLength($city, self::CITY_LENGTH_MAX);
        Validator::checkWhitespacesAndLength($streetAddress, self::STREET_ADDRESS_LENGTH_MAX);
        Validator::checkWhitespacesAndLength($zip, self::ZIP_LENGTH_MAX);
        if ($name !== null) {
            Validator::checkWhitespacesAndLength($name, self::NAME_LENGTH_MAX);
        }
        if ($streetNumber !== null) {
            Validator::checkWhitespacesAndLength($streetNumber, self::STREET_NUMBER_LENGTH_MAX);
        }
    }

    /**
     * @return mixed[]
     */
    public function encode(): array
    {
        return array_filter([
            'name'          => $this->name,
            'country'       => $this->country,
            'city'          => $this->city,
            'streetAddress' => $this->streetAddress,
            'streetNumber'  => $this->streetNumber,
            'zip'           => $this->zip,
            'addressType'   => $this->addressType,
        ]);
    }

    /**
     * @return mixed[]
     */
    public static function encodeForSignature(): array
    {
        return [
            'name'          => null,
            'country'       => null,
            'city'          => null,
            'streetAddress' => null,
            'streetNumber'  => null,
            'zip'           => null,
            'addressType'   => null,
        ];
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getStreetAddress(): string
    {
        return $this->streetAddress;
    }

    public function getStreetNumber(): ?string
    {
        return $this->streetNumber;
    }

    public function getZip(): string
    {
        return $this->zip;
    }

    public function getAddressType(): string
    {
        return $this->addressType;
    }

}
