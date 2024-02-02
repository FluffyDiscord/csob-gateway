<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call\MallPay;

use InvalidArgumentException;
use SlevomatCsobGateway\Call\StatusDetailPaymentResponse;
use SlevomatCsobGateway\Crypto\SignatureDataFormatter;
use SlevomatCsobGateway\EncodeHelper;
use SlevomatCsobGateway\MallPay\OrderItemReference;
use SlevomatCsobGateway\Validator;
use function array_filter;
use function array_map;

class RefundMallPayRequest
{

    /**
     * @var string
     */
    private $merchantId;
    /**
     * @var string
     */
    private $payId;
    /**
     * @var int|null
     */
    private $amount;
    /**
     * @var OrderItemReference[]
     */
    private $refundedItems;

    /**
     * @param OrderItemReference[] $refundedItems
     */
    public function __construct(string $merchantId, string $payId, ?int $amount, array $refundedItems)
    {
        $this->merchantId = $merchantId;
        $this->payId = $payId;
        $this->amount = $amount;
        $this->refundedItems = $refundedItems;
        if ($amount !== null) {
            Validator::checkNumberPositiveOrZero($amount);
        }
        if ($this->refundedItems === []) {
            throw new InvalidArgumentException('Refund has no items.');
        }
    }

    /**
     * @param \SlevomatCsobGateway\Api\ApiClient $apiClient
     */
    public function send($apiClient): StatusDetailPaymentResponse
    {
        $requestData = array_filter([
            'merchantId'    => $this->merchantId,
            'payId'         => $this->payId,
            'amount'        => $this->amount,
            'refundedItems' => array_map(static function (OrderItemReference $item): array {
                return $item->encode();
            }, $this->refundedItems),
        ], EncodeHelper::filterValueCallback() === null ? function ($value, $key): bool {
            return !empty($value);
        } : EncodeHelper::filterValueCallback(), EncodeHelper::filterValueCallback() === null ? ARRAY_FILTER_USE_BOTH : 0);

        $response = $apiClient->put('mallpay/refund', $requestData, new SignatureDataFormatter([
            'merchantId'    => null,
            'payId'         => null,
            'dttm'          => null,
            'amount'        => null,
            'refundedItems' => [
                OrderItemReference::encodeForSignature(),
            ],
        ]), new SignatureDataFormatter(StatusDetailPaymentResponse::encodeForSignature()));

        /** @var mixed[] $data */
        $data = $response->getData();

        return StatusDetailPaymentResponse::createFromResponseData($data);
    }

}
