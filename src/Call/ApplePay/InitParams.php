<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call\ApplePay;

use SlevomatCsobGateway\Encodable;
use SlevomatCsobGateway\EncodeHelper;
use function array_filter;

class InitParams implements Encodable
{

    /**
     * @var string
     */
    private $countryCode;
    /**
     * @var string[]
     */
    private $supportedNetworks;
    /**
     * @var string[]
     */
    private $merchantCapabilities;

    /**
     * @param string[] $supportedNetworks
     * @param string[] $merchantCapabilities
     */
    public function __construct(string $countryCode, array $supportedNetworks, array $merchantCapabilities)
    {
        $this->countryCode = $countryCode;
        $this->supportedNetworks = $supportedNetworks;
        $this->merchantCapabilities = $merchantCapabilities;
    }

    /**
     * @return mixed[]
     */
    public static function encodeForSignature(): array
    {
        return [
            'countryCode'          => null,
            'supportedNetworks'    => [],
            'merchantCapabilities' => [],
        ];
    }

    /**
     * @return mixed[]
     */
    public function encode(): array
    {
        return array_filter([
            'countryCode'          => $this->countryCode,
            'supportedNetworks'    => $this->supportedNetworks,
            'merchantCapabilities' => $this->merchantCapabilities,
        ], EncodeHelper::filterValueCallback() === null ? function ($value, $key): bool {
            return !empty($value);
        } : EncodeHelper::filterValueCallback(), EncodeHelper::filterValueCallback() === null ? ARRAY_FILTER_USE_BOTH : 0);
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    /**
     * @return string[]
     */
    public function getSupportedNetworks(): array
    {
        return $this->supportedNetworks;
    }

    /**
     * @return string[]
     */
    public function getMerchantCapabilities(): array
    {
        return $this->merchantCapabilities;
    }

}
