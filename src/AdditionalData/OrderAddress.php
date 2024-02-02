<?php declare(strict_types=1);

namespace SlevomatCsobGateway\AdditionalData;

use SlevomatCsobGateway\Country;
use SlevomatCsobGateway\Encodable;
use SlevomatCsobGateway\EncodeHelper;
use SlevomatCsobGateway\Validator;
use function array_filter;

class OrderAddress implements Encodable
{

    /**
     * @var string
     */
    private $address1;
    /**
     * @var string|null
     */
    private $address2;
    /**
     * @var string|null
     */
    private $address3;
    /**
     * @var string
     */
    private $city;
    /**
     * @var string
     */
    private $zip;
    /**
     * @var string|null
     */
    private $state;
    /**
     * @var string
     */
    private $country;
    public const ADDRESS_LENGTH_MAX = 50;
    public const ZIP_LENGTH_MAX = 50;

    public function __construct(string $address1, ?string $address2, ?string $address3, string $city, string $zip, ?string $state, string $country)
    {
        $this->address1 = $address1;
        $this->address2 = $address2;
        $this->address3 = $address3;
        $this->city = $city;
        $this->zip = $zip;
        $this->state = $state;
        $this->country = $country;
        Validator::checkWhitespacesAndLength($this->address1, self::ADDRESS_LENGTH_MAX);
        if ($this->address2 !== null) {
            Validator::checkWhitespacesAndLength($this->address2, self::ADDRESS_LENGTH_MAX);
        }
        if ($this->address3 !== null) {
            Validator::checkWhitespacesAndLength($this->address3, self::ADDRESS_LENGTH_MAX);
        }
        if ($this->city !== null) {
            Validator::checkWhitespacesAndLength($this->city, self::ADDRESS_LENGTH_MAX);
        }
        Validator::checkWhitespacesAndLength($this->zip, self::ZIP_LENGTH_MAX);
    }

    /**
     * @return mixed[]
     */
    public function encode(): array
    {
        return array_filter([
            'address1' => $this->address1,
            'address2' => $this->address2,
            'address3' => $this->address3,
            'city'     => $this->city,
            'zip'      => $this->zip,
            'state'    => $this->state,
            'country'  => Country::getLongCode($this->country),
        ], EncodeHelper::filterValueCallback() === null ? function ($value, $key): bool {
            return !empty($value);
        } : EncodeHelper::filterValueCallback(), EncodeHelper::filterValueCallback() === null ? ARRAY_FILTER_USE_BOTH : 0);
    }

    /**
     * @return mixed[]
     */
    public static function encodeForSignature(): array
    {
        return [
            'address1' => null,
            'address2' => null,
            'address3' => null,
            'city'     => null,
            'zip'      => null,
            'state'    => null,
            'country'  => null,
        ];
    }

}
