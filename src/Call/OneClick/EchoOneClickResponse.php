<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call\OneClick;

use DateTimeImmutable;
use SlevomatCsobGateway\Call\Response;
use SlevomatCsobGateway\Call\ResultCode;
use SlevomatCsobGateway\EncodeHelper;
use SlevomatCsobGateway\Validator;
use function array_filter;

class EchoOneClickResponse implements Response
{

    /**
     * @var string
     */
    private $origPayId;
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

    public function __construct(string $origPayId, DateTimeImmutable $responseDateTime, int $resultCode, string $resultMessage)
    {
        $this->origPayId = $origPayId;
        $this->responseDateTime = $responseDateTime;
        $this->resultCode = $resultCode;
        $this->resultMessage = $resultMessage;
        Validator::checkPayId($this->origPayId);
    }

    /**
     * @param mixed[] $data
     * @return $this
     */
    public static function createFromResponseData($data): \SlevomatCsobGateway\Call\Response
    {
        return new self($data['origPayId'], DateTimeImmutable::createFromFormat('YmdHis', $data['dttm']), $data['resultCode'], $data['resultMessage']);
    }

    /**
     * @return mixed[]
     */
    public static function encodeForSignature(): array
    {
        return [
            'origPayId'     => null,
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
            'origPayId'     => $this->origPayId,
            'dttm'          => $this->responseDateTime->format('YmdHis'),
            'resultCode'    => $this->resultCode,
            'resultMessage' => $this->resultMessage,
        ], EncodeHelper::filterValueCallback() === null ? function ($value, $key): bool {
            return !empty($value);
        } : EncodeHelper::filterValueCallback(), EncodeHelper::filterValueCallback() === null ? ARRAY_FILTER_USE_BOTH : 0);
    }

    public function getOrigPayId(): string
    {
        return $this->origPayId;
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
