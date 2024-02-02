<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call;

use DateTimeImmutable;
use SlevomatCsobGateway\EncodeHelper;
use function array_filter;

class EchoResponse implements Response
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

    public function __construct(DateTimeImmutable $responseDateTime, int $resultCode, string $resultMessage)
    {
        $this->responseDateTime = $responseDateTime;
        $this->resultCode = $resultCode;
        $this->resultMessage = $resultMessage;
    }

    /**
     * @param mixed[] $data
     * @return $this
     */
    public static function createFromResponseData($data): \SlevomatCsobGateway\Call\Response
    {
        return new self(DateTimeImmutable::createFromFormat('YmdHis', $data['dttm']), $data['resultCode'], $data['resultMessage']);
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

}
