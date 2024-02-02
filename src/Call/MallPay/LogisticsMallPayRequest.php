<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call\MallPay;

use DateTimeImmutable;
use InvalidArgumentException;
use SlevomatCsobGateway\Call\StatusDetailPaymentResponse;
use SlevomatCsobGateway\Crypto\SignatureDataFormatter;
use SlevomatCsobGateway\EncodeHelper;
use SlevomatCsobGateway\MallPay\LogisticsEvent;
use SlevomatCsobGateway\MallPay\OrderReference;
use function array_filter;

class LogisticsMallPayRequest
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
     * @var string
     */
    private $event;
    /**
     * @var \DateTimeImmutable
     */
    private $date;
    /**
     * @var \SlevomatCsobGateway\MallPay\OrderReference
     */
    private $fulfilled;
    /**
     * @var \SlevomatCsobGateway\MallPay\OrderReference|null
     */
    private $cancelled;
    /**
     * @var string|null
     */
    private $deliveryTrackingNumber;

    public function __construct(string $merchantId, string $payId, string $event, DateTimeImmutable $date, OrderReference $fulfilled, ?OrderReference $cancelled = null, ?string $deliveryTrackingNumber = null)
    {
        $this->merchantId = $merchantId;
        $this->payId = $payId;
        $this->event = $event;
        $this->date = $date;
        $this->fulfilled = $fulfilled;
        $this->cancelled = $cancelled;
        $this->deliveryTrackingNumber = $deliveryTrackingNumber;
        if ($fulfilled->getItems() === []) {
            throw new InvalidArgumentException('Fulfilled has no items.');
        }
        if ($cancelled !== null && $cancelled->getItems() === []) {
            throw new InvalidArgumentException('Cancelled has no items.');
        }
    }

    /**
     * @param \SlevomatCsobGateway\Api\ApiClient $apiClient
     */
    public function send($apiClient): StatusDetailPaymentResponse
    {
        $requestData = array_filter([
            'merchantId'             => $this->merchantId,
            'payId'                  => $this->payId,
            'event'                  => $this->event,
            'date'                   => $this->date->format('Ymd'),
            'fulfilled'              => $this->fulfilled->encode(),
            'cancelled'              => ($nullsafeVariable1 = $this->cancelled) ? $nullsafeVariable1->encode() : null,
            'deliveryTrackingNumber' => $this->deliveryTrackingNumber,
        ], EncodeHelper::filterValueCallback() === null ? function ($value, $key): bool {
            return !empty($value);
        } : EncodeHelper::filterValueCallback(), EncodeHelper::filterValueCallback() === null ? ARRAY_FILTER_USE_BOTH : 0);

        $response = $apiClient->put('mallpay/logistics', $requestData, new SignatureDataFormatter([
            'merchantId'             => null,
            'payId'                  => null,
            'event'                  => null,
            'date'                   => null,
            'fulfilled'              => OrderReference::encodeForSignature(),
            'cancelled'              => OrderReference::encodeForSignature(),
            'deliveryTrackingNumber' => null,
            'dttm'                   => null,
        ]), new SignatureDataFormatter(StatusDetailPaymentResponse::encodeForSignature()));

        /** @var mixed[] $data */
        $data = $response->getData();

        return StatusDetailPaymentResponse::createFromResponseData($data);
    }

}
