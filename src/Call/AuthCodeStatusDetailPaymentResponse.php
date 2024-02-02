<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call;

use DateTimeImmutable;
use SlevomatCsobGateway\EncodeHelper;
use function array_filter;
use function array_merge;

class AuthCodeStatusDetailPaymentResponse extends StatusDetailPaymentResponse
{

    /**
     * @var string|null
     */
    private $authCode;

    public function __construct(string $payId, DateTimeImmutable $responseDateTime, int $resultCode, string $resultMessage, ?int $paymentStatus = null, ?string $authCode = null, ?string $statusDetail = null)
    {
        $this->authCode = $authCode;
        parent::__construct($payId, $responseDateTime, $resultCode, $resultMessage, $paymentStatus, $statusDetail);
    }

    /**
     * @param mixed[] $data
     * @return $this
     */
    public static function createFromResponseData($data): \SlevomatCsobGateway\Call\Response
    {
        $statusDetailPaymentResponse = parent::createFromResponseData($data);

        return new self($statusDetailPaymentResponse->getPayId(), $statusDetailPaymentResponse->getResponseDateTime(), $statusDetailPaymentResponse->getResultCode(), $statusDetailPaymentResponse->getResultMessage(), $statusDetailPaymentResponse->getPaymentStatus(), $data['authCode'] ?? null, $statusDetailPaymentResponse->getStatusDetail());
    }

    /**
     * @return mixed[]
     */
    public static function encodeForSignature(): array
    {
        $statusDetailPaymentData = parent::encodeForSignature();
        unset($statusDetailPaymentData['statusDetail']);

        return array_merge($statusDetailPaymentData, [
            'authCode'     => null,
            'statusDetail' => null,
        ]);
    }

    /**
     * @return mixed[]
     */
    public function encode(): array
    {
        return array_filter(array_merge(parent::encode(), [
            'authCode' => $this->authCode,
        ]), EncodeHelper::filterValueCallback() === null ? function ($value, $key): bool {
            return !empty($value);
        } : EncodeHelper::filterValueCallback(), EncodeHelper::filterValueCallback() === null ? ARRAY_FILTER_USE_BOTH : 0);
    }

    public function getAuthCode(): ?string
    {
        return $this->authCode;
    }

}
