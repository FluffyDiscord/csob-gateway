<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call;

use DateTimeImmutable;
use SlevomatCsobGateway\EncodeHelper;
use SlevomatCsobGateway\Validator;
use function array_filter;
use function array_key_exists;

class PaymentResponse implements Response
{

    /**
     * @var string
     */
    private $payId;
    /**
     * @var \DateTimeImmutable
     */
    private $responseDateTime;
    /**
     * @var string
     */
    private $resultCode;
    /**
     * @var string
     */
    private $resultMessage;
    /**
     * @var string|null
     */
    private $paymentStatus;

    public function __construct(string $payId, DateTimeImmutable $responseDateTime, int $resultCode, string $resultMessage, ?int $paymentStatus = null)
    {
        $this->payId = $payId;
        $this->responseDateTime = $responseDateTime;
        $this->resultCode = $resultCode;
        $this->resultMessage = $resultMessage;
        $this->paymentStatus = $paymentStatus;
        Validator::checkPayId($payId);
    }

    /**
     * @param mixed[] $data
     * @return $this
     */
    public static function createFromResponseData($data): \SlevomatCsobGateway\Call\Response
    {
        return new self(
            $data['payId'] ?? '',
            // for some error response it can be null
            DateTimeImmutable::createFromFormat('YmdHis', $data['dttm']),
            $data['resultCode'],
            $data['resultMessage'],
            $data['paymentStatus'] ?? null
        );
    }

    /**
     * @return mixed[]
     */
    public static function encodeForSignature(): array
    {
        return [
            'payId'         => null,
            'dttm'          => null,
            'resultCode'    => null,
            'resultMessage' => null,
            'paymentStatus' => null,
        ];
    }

    /**
     * @return mixed[]
     */
    public function encode(): array
    {
        return array_filter([
            'payId'         => $this->payId,
            'dttm'          => $this->responseDateTime->format('YmdHis'),
            'resultCode'    => $this->resultCode,
            'resultMessage' => $this->resultMessage,
            'paymentStatus' => ($nullsafeVariable1 = $this->paymentStatus) ? $nullsafeVariable1 : null,
        ], EncodeHelper::filterValueCallback() === null ? function ($value, $key): bool {
            return !empty($value);
        } : EncodeHelper::filterValueCallback(), EncodeHelper::filterValueCallback() === null ? ARRAY_FILTER_USE_BOTH : 0);
    }

    public function getPayId(): string
    {
        return $this->payId;
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

    public function getPaymentStatus(): ?int
    {
        return $this->paymentStatus;
    }

}
