<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call\MallPay;

use SlevomatCsobGateway\Call\StatusDetailPaymentResponse;
use SlevomatCsobGateway\Crypto\SignatureDataFormatter;

class CancelMallPayRequest
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
    private $reason;

    public function __construct(string $merchantId, string $payId, string $reason)
    {
        $this->merchantId = $merchantId;
        $this->payId = $payId;
        $this->reason = $reason;
    }

    /**
     * @param \SlevomatCsobGateway\Api\ApiClient $apiClient
     */
    public function send($apiClient): StatusDetailPaymentResponse
    {
        $requestData = [
            'merchantId' => $this->merchantId,
            'payId'      => $this->payId,
            'reason'     => $this->reason,
        ];

        $response = $apiClient->put('mallpay/cancel', $requestData, new SignatureDataFormatter([
            'merchantId' => null,
            'payId'      => null,
            'reason'     => null,
            'dttm'       => null,
        ]), new SignatureDataFormatter(StatusDetailPaymentResponse::encodeForSignature()));

        /** @var mixed[] $data */
        $data = $response->getData();

        return StatusDetailPaymentResponse::createFromResponseData($data);
    }

}
