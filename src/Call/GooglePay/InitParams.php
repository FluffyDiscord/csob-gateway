<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call\GooglePay;

use SlevomatCsobGateway\Encodable;
use SlevomatCsobGateway\EncodeHelper;
use function array_filter;

class InitParams implements Encodable
{

    /**
     * @var int
     */
    private $apiVersion;
    /**
     * @var int
     */
    private $apiVersionMinor;
    /**
     * @var string
     */
    private $paymentMethodType;
    /**
     * @var string[]
     */
    private $allowedCardNetworks;
    /**
     * @var string[]
     */
    private $allowedCardAuthMethods;
    /**
     * @var bool
     */
    private $assuranceDetailsRequired;
    /**
     * @var bool
     */
    private $billingAddressRequired;
    /**
     * @var string
     */
    private $billingAddressParametersFormat;
    /**
     * @var string
     */
    private $tokenizationSpecificationType;
    /**
     * @var string
     */
    private $gateway;
    /**
     * @var string
     */
    private $gatewayMerchantId;
    /**
     * @var string
     */
    private $googlepayMerchantId;
    /**
     * @var string
     */
    private $merchantName;
    /**
     * @var string
     */
    private $environment;
    /**
     * @var string
     */
    private $totalPriceStatus;
    /**
     * @var string
     */
    private $countryCode;

    /**
     * @param string[] $allowedCardNetworks
     * @param string[] $allowedCardAuthMethods
     */
    public function __construct(int $apiVersion, int $apiVersionMinor, string $paymentMethodType, array $allowedCardNetworks, array $allowedCardAuthMethods, bool $assuranceDetailsRequired, bool $billingAddressRequired, string $billingAddressParametersFormat, string $tokenizationSpecificationType, string $gateway, string $gatewayMerchantId, string $googlepayMerchantId, string $merchantName, string $environment, string $totalPriceStatus, string $countryCode)
    {
        $this->apiVersion = $apiVersion;
        $this->apiVersionMinor = $apiVersionMinor;
        $this->paymentMethodType = $paymentMethodType;
        $this->allowedCardNetworks = $allowedCardNetworks;
        $this->allowedCardAuthMethods = $allowedCardAuthMethods;
        $this->assuranceDetailsRequired = $assuranceDetailsRequired;
        $this->billingAddressRequired = $billingAddressRequired;
        $this->billingAddressParametersFormat = $billingAddressParametersFormat;
        $this->tokenizationSpecificationType = $tokenizationSpecificationType;
        $this->gateway = $gateway;
        $this->gatewayMerchantId = $gatewayMerchantId;
        $this->googlepayMerchantId = $googlepayMerchantId;
        $this->merchantName = $merchantName;
        $this->environment = $environment;
        $this->totalPriceStatus = $totalPriceStatus;
        $this->countryCode = $countryCode;
    }

    /**
     * @return mixed[]
     */
    public static function encodeForSignature(): array
    {
        return [
            'apiVersion'                     => null,
            'apiVersionMinor'                => null,
            'paymentMethodType'              => null,
            'allowedCardNetworks'            => [],
            'allowedCardAuthMethods'         => [],
            'assuranceDetailsRequired'       => null,
            'billingAddressRequired'         => null,
            'billingAddressParametersFormat' => null,
            'tokenizationSpecificationType'  => null,
            'gateway'                        => null,
            'gatewayMerchantId'              => null,
            'googlepayMerchantId'            => null,
            'merchantName'                   => null,
            'environment'                    => null,
            'totalPriceStatus'               => null,
            'countryCode'                    => null,
        ];
    }

    /**
     * @return mixed[]
     */
    public function encode(): array
    {
        return array_filter([
            'apiVersion'                     => $this->apiVersion,
            'apiVersionMinor'                => $this->apiVersionMinor,
            'paymentMethodType'              => $this->paymentMethodType,
            'allowedCardNetworks'            => $this->allowedCardNetworks,
            'allowedCardAuthMethods'         => $this->allowedCardAuthMethods,
            'assuranceDetailsRequired'       => $this->assuranceDetailsRequired,
            'billingAddressRequired'         => $this->billingAddressRequired,
            'billingAddressParametersFormat' => $this->billingAddressParametersFormat,
            'tokenizationSpecificationType'  => $this->tokenizationSpecificationType,
            'gateway'                        => $this->gateway,
            'gatewayMerchantId'              => $this->gatewayMerchantId,
            'googlepayMerchantId'            => $this->googlepayMerchantId,
            'merchantName'                   => $this->merchantName,
            'environment'                    => $this->environment,
            'totalPriceStatus'               => $this->totalPriceStatus,
            'countryCode'                    => $this->countryCode,
        ], EncodeHelper::filterValueCallback() === null ? function ($value, $key): bool {
            return !empty($value);
        } : EncodeHelper::filterValueCallback(), EncodeHelper::filterValueCallback() === null ? ARRAY_FILTER_USE_BOTH : 0);
    }

    public function getApiVersion(): int
    {
        return $this->apiVersion;
    }

    public function getApiVersionMinor(): int
    {
        return $this->apiVersionMinor;
    }

    public function getPaymentMethodType(): string
    {
        return $this->paymentMethodType;
    }

    /**
     * @return string[]
     */
    public function getAllowedCardNetworks(): array
    {
        return $this->allowedCardNetworks;
    }

    /**
     * @return string[]
     */
    public function getAllowedCardAuthMethods(): array
    {
        return $this->allowedCardAuthMethods;
    }

    public function isAssuranceDetailsRequired(): bool
    {
        return $this->assuranceDetailsRequired;
    }

    public function isBillingAddressRequired(): bool
    {
        return $this->billingAddressRequired;
    }

    public function getBillingAddressParametersFormat(): string
    {
        return $this->billingAddressParametersFormat;
    }

    public function getTokenizationSpecificationType(): string
    {
        return $this->tokenizationSpecificationType;
    }

    public function getGateway(): string
    {
        return $this->gateway;
    }

    public function getGatewayMerchantId(): string
    {
        return $this->gatewayMerchantId;
    }

    public function getGooglepayMerchantId(): string
    {
        return $this->googlepayMerchantId;
    }

    public function getMerchantName(): string
    {
        return $this->merchantName;
    }

    public function getEnvironment(): string
    {
        return $this->environment;
    }

    public function getTotalPriceStatus(): string
    {
        return $this->totalPriceStatus;
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

}
