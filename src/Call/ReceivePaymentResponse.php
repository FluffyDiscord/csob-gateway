<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call;

use DateTimeImmutable;
use SlevomatCsobGateway\EncodeHelper;
use function array_filter;
use function array_key_exists;
use function array_merge;
use function base64_decode;

class ReceivePaymentResponse extends AuthCodeStatusDetailPaymentResponse
{

    /**
     * @var string|null
     */
    private $merchantData;

    public function __construct(string $payId, DateTimeImmutable $responseDateTime, int $resultCode, string $resultMessage, ?int $paymentStatus, ?string $authCode = null, ?string $merchantData = null, ?string $statusDetail = null)
    {
        $this->merchantData = $merchantData;
        parent::__construct($payId, $responseDateTime, $resultCode, $resultMessage, $paymentStatus, $authCode, $statusDetail);
    }

    /**
     * @param mixed[] $data
     * @return $this
     */
    public static function createFromResponseData($data): \SlevomatCsobGateway\Call\Response
    {
        $paymentResponse = parent::createFromResponseData($data);

        return new self($paymentResponse->getPayId(), $paymentResponse->getResponseDateTime(), $paymentResponse->getResultCode(), $paymentResponse->getResultMessage(), $paymentResponse->getPaymentStatus(), $paymentResponse->getAuthCode(), array_key_exists('merchantData', $data) ? (string)base64_decode($data['merchantData'], true) : null, $paymentResponse->getStatusDetail());
    }

    /**
     * @return mixed[]
     */
    public static function encodeForSignature(): array
    {
        return ProcessPaymentResponse::encodeForSignature();
    }

    /**
     * @return mixed[]
     */
    public function encode(): array
    {
        return array_filter(array_merge(parent::encode(), [
            'merchantData' => $this->merchantData,
        ]), EncodeHelper::filterValueCallback() === null ? function ($value, $key): bool {
            return !empty($value);
        } : EncodeHelper::filterValueCallback(), EncodeHelper::filterValueCallback() === null ? ARRAY_FILTER_USE_BOTH : 0);
    }

    public function getMerchantData(): ?string
    {
        return $this->merchantData;
    }

}
