<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call;

use DateTimeImmutable;
use SlevomatCsobGateway\EncodeHelper;
use function array_filter;
use function array_merge;

class PaymentStatusResponse extends ActionsPaymentResponse
{

    /**
     * @var string|null
     */
    private $authCode;
    /**
     * @var mixed[]
     */
    private $extensions = [];

    /**
     * @param mixed[] $extensions
     */
    public function __construct(string $payId, DateTimeImmutable $responseDateTime, int $resultCode, string $resultMessage, ?int $paymentStatus = null, ?string $authCode = null, ?string $statusDetail = null, ?Actions $actions = null, array $extensions = [])
    {
        $this->authCode = $authCode;
        $this->extensions = $extensions;
        parent::__construct($payId, $responseDateTime, $resultCode, $resultMessage, $paymentStatus, $statusDetail, $actions);
    }

    /**
     * @param mixed[] $data
     * @return $this
     */
    public static function createFromResponseData($data): \SlevomatCsobGateway\Call\Response
    {
        $actionsPaymentResponse = parent::createFromResponseData($data);

        return new self($actionsPaymentResponse->getPayId(), $actionsPaymentResponse->getResponseDateTime(), $actionsPaymentResponse->getResultCode(), $actionsPaymentResponse->getResultMessage(), $actionsPaymentResponse->getPaymentStatus(), $data['authCode'] ?? null, $actionsPaymentResponse->getStatusDetail(), $actionsPaymentResponse->getActions(), $data['extensions']);
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
            'authCode'      => null,
            'statusDetail'  => null,
            'actions'       => Actions::encodeForSignature(),
        ];
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

    /**
     * @return mixed[]
     */
    public function getExtensions(): array
    {
        return $this->extensions;
    }

}
