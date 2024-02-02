<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call\MallPay;

use DateTimeImmutable;
use SlevomatCsobGateway\Call\PaymentResponse;
use SlevomatCsobGateway\Call\PaymentStatus;
use SlevomatCsobGateway\Call\ResultCode;
use SlevomatCsobGateway\EncodeHelper;
use function array_filter;
use function array_merge;

class InitMallPayResponse extends PaymentResponse
{

    /**
     * @var string|null
     */
    private $mallpayUrl;

    public function __construct(string $payId, DateTimeImmutable $responseDateTime, int $resultCode, string $resultMessage, ?int $paymentStatus, ?string $mallpayUrl = null)
    {
        $this->mallpayUrl = $mallpayUrl;
        parent::__construct($payId, $responseDateTime, $resultCode, $resultMessage, $paymentStatus);
    }

    /**
     * @param mixed[] $data
     * @return $this
     */
    public static function createFromResponseData($data): \SlevomatCsobGateway\Call\Response
    {
        $paymentResponse = parent::createFromResponseData($data);

        return new self($paymentResponse->getPayId(), $paymentResponse->getResponseDateTime(), $paymentResponse->getResultCode(), $paymentResponse->getResultMessage(), $paymentResponse->getPaymentStatus(), $data['mallpayUrl'] ?? null);
    }

    /**
     * @return mixed[]
     */
    public static function encodeForSignature(): array
    {
        return array_merge(parent::encodeForSignature(), [
            'mallpayUrl' => null,
        ]);
    }

    /**
     * @return mixed[]
     */
    public function encode(): array
    {
        return array_filter(array_merge(parent::encode(), [
            'mallpayUrl' => $this->mallpayUrl,
        ]), EncodeHelper::filterValueCallback() === null ? function ($value, $key): bool {
            return !empty($value);
        } : EncodeHelper::filterValueCallback(), EncodeHelper::filterValueCallback() === null ? ARRAY_FILTER_USE_BOTH : 0);
    }

    public function getMallpayUrl(): ?string
    {
        return $this->mallpayUrl;
    }

}
