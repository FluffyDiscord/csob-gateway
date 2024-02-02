<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call;

use DateTimeImmutable;
use SlevomatCsobGateway\EncodeHelper;
use function array_filter;
use function array_merge;

class InitPaymentResponse extends PaymentResponse
{

    /**
     * @var string|null
     */
    private $customerCode;
    /**
     * @var string|null
     */
    private $statusDetail;

    public function __construct(string $payId, DateTimeImmutable $responseDateTime, int $resultCode, string $resultMessage, ?int $paymentStatus, ?string $customerCode, ?string $statusDetail)
    {
        $this->customerCode = $customerCode;
        $this->statusDetail = $statusDetail;
        parent::__construct($payId, $responseDateTime, $resultCode, $resultMessage, $paymentStatus);
    }

    /**
     * @param mixed[] $data
     * @return $this
     */
    public static function createFromResponseData($data): \SlevomatCsobGateway\Call\Response
    {
        $paymentResponse = parent::createFromResponseData($data);

        return new self($paymentResponse->getPayId(), $paymentResponse->getResponseDateTime(), $paymentResponse->getResultCode(), $paymentResponse->getResultMessage(), $paymentResponse->getPaymentStatus(), $data['customerCode'] ?? null, $data['statusDetail'] ?? null);
    }

    /**
     * @return mixed[]
     */
    public static function encodeForSignature(): array
    {
        return array_merge(parent::encodeForSignature(), [
            'customerCode' => null,
            'statusDetail' => null,
        ]);
    }

    /**
     * @return mixed[]
     */
    public function encode(): array
    {
        return array_filter(array_merge(parent::encode(), [
            'customerCode' => $this->customerCode,
            'statusDetail' => $this->statusDetail,
        ]), EncodeHelper::filterValueCallback() === null ? function ($value, $key): bool {
            return !empty($value);
        } : EncodeHelper::filterValueCallback(), EncodeHelper::filterValueCallback() === null ? ARRAY_FILTER_USE_BOTH : 0);
    }

    public function getCustomerCode(): ?string
    {
        return $this->customerCode;
    }

    public function getStatusDetail(): ?string
    {
        return $this->statusDetail;
    }

}
