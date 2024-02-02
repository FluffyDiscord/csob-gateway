<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call\ApplePay;

use DateTimeImmutable;
use SlevomatCsobGateway\EncodeHelper;
use function array_filter;
use function array_key_exists;

class EchoApplePayResponse
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
     * @var \SlevomatCsobGateway\Call\ApplePay\InitParams|null
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
        return new self(DateTimeImmutable::createFromFormat('YmdHis', $data['dttm']), $data['resultCode'], $data['resultMessage'], array_key_exists('initParams', $data) ? new InitParams($data['initParams']['countryCode'], $data['initParams']['supportedNetworks'], $data['initParams']['merchantCapabilities']) : null);
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
