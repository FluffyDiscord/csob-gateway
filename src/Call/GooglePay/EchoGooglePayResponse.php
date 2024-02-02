<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call\GooglePay;

use DateTimeImmutable;
use SlevomatCsobGateway\EncodeHelper;
use function array_filter;
use function array_key_exists;

class EchoGooglePayResponse
{

    /**
     * @var \DateTimeImmutable
     */
    private $responseDateTime;
    /**
     * @var int
     */
    private $resultCode;
    /**
     * @var string
     */
    private $resultMessage;
    /**
     * @var \SlevomatCsobGateway\Call\GooglePay\InitParams|null
     */
    private $initParams;

    public function __construct(DateTimeImmutable $responseDateTime, int $resultCode, string $resultMessage, ?InitParams $initParams = null)
    {
        $this->responseDateTime = $responseDateTime;
        $this->resultCode = $resultCode;
        $this->resultMessage = $resultMessage;
        $this->initParams = $initParams;
    }

    /**
     * @param mixed[] $data
     */
    public static function createFromResponseData($data): self
    {
        return new self(DateTimeImmutable::createFromFormat('YmdHis', $data['dttm']), $data['resultCode'], $data['resultMessage'], array_key_exists('initParams', $data) ? new InitParams($data['initParams']['apiVersion'], $data['initParams']['apiVersionMinor'], $data['initParams']['paymentMethodType'], $data['initParams']['allowedCardNetworks'], $data['initParams']['allowedCardAuthMethods'], $data['initParams']['assuranceDetailsRequired'], $data['initParams']['billingAddressRequired'], $data['initParams']['billingAddressParametersFormat'], $data['initParams']['tokenizationSpecificationType'], $data['initParams']['gateway'], $data['initParams']['gatewayMerchantId'], $data['initParams']['googlepayMerchantId'], $data['initParams']['merchantName'], $data['initParams']['environment'], $data['initParams']['totalPriceStatus'], $data['initParams']['countryCode']) : null);
    }

    /**
     * @return mixed[]
     */
    public static function encodeForSignature(): array
    {
        return [
            'dttm'          => null,
            'resultCode'    => null,
            'resultMessage' => null,
            'initParams'    => InitParams::encodeForSignature(),
        ];
    }

    /**
     * @return mixed[]
     */
    public function encode(): array
    {
        return array_filter([
            'dttm'          => $this->responseDateTime->format('YmdHis'),
            'resultCode'    => $this->resultCode,
            'resultMessage' => $this->resultMessage,
            'initParams'    => ($nullsafeVariable1 = $this->initParams) ? $nullsafeVariable1->encode() : null,
        ], EncodeHelper::filterValueCallback() === null ? function ($value, $key): bool {
            return !empty($value);
        } : EncodeHelper::filterValueCallback(), EncodeHelper::filterValueCallback() === null ? ARRAY_FILTER_USE_BOTH : 0);
    }

    public function getResponseDateTime(): DateTimeImmutable
    {
        return $this->responseDateTime;
    }

    public function getResultCode(): int
    {
        return $this->resultCode;
    }

    public function getResultMessage(): string
    {
        return $this->resultMessage;
    }

    public function getInitParams(): ?InitParams
    {
        return $this->initParams;
    }

}
