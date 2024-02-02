<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call;

use DateTimeImmutable;
use SlevomatCsobGateway\EncodeHelper;
use SlevomatCsobGateway\Validator;
use function array_filter;

class EchoCustomerResponse implements Response
{

    /**
     * @var string
     */
    private $customerId;
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

    public function __construct(string $customerId, DateTimeImmutable $responseDateTime, int $resultCode, string $resultMessage)
    {
        $this->customerId = $customerId;
        $this->responseDateTime = $responseDateTime;
        $this->resultCode = $resultCode;
        $this->resultMessage = $resultMessage;
        Validator::checkCustomerId($customerId);
    }

    /**
     * @param mixed[] $data
     * @return $this
     */
    public static function createFromResponseData($data): \SlevomatCsobGateway\Call\Response
    {
        return new self($data['customerId'], DateTimeImmutable::createFromFormat('YmdHis', $data['dttm']), $data['resultCode'], $data['resultMessage']);
    }

    /**
     * @return mixed[]
     */
    public static function encodeForSignature(): array
    {
        return [
            'customerId'    => null,
            'dttm'          => null,
            'resultCode'    => null,
            'resultMessage' => null,
        ];
    }

    /**
     * @return mixed[]
     */
    public function encode(): array
    {
        return array_filter([
            'customerId'    => $this->customerId,
            'dttm'          => $this->responseDateTime->format('YmdHis'),
            'resultCode'    => $this->resultCode,
            'resultMessage' => $this->resultMessage,
        ], EncodeHelper::filterValueCallback() === null ? function ($value, $key): bool {
            return !empty($value);
        } : EncodeHelper::filterValueCallback(), EncodeHelper::filterValueCallback() === null ? ARRAY_FILTER_USE_BOTH : 0);
    }

    public function getCustomerId(): string
    {
        return $this->customerId;
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

}
