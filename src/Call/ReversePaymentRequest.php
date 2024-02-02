<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call;

use SlevomatCsobGateway\Crypto\SignatureDataFormatter;
use SlevomatCsobGateway\Validator;

class ReversePaymentRequest
{

    /**
     * @var string
     */
    private $merchantId;
    /**
     * @var string
     */
    private $payId;

    public function __construct(string $merchantId, string $payId)
    {
        $this->merchantId = $merchantId;
        $this->payId = $payId;
        Validator::checkPayId($payId);
    }

    /**
     * @param \SlevomatCsobGateway\Api\ApiClient $apiClient
     */
    public function send($apiClient): StatusDetailPaymentResponse
    {
        $response = $apiClient->put('payment/reverse', [
            'merchantId' => $this->merchantId,
            'payId'      => $this->payId,
        ], new SignatureDataFormatter([
            'merchantId' => null,
            'payId'      => null,
            'dttm'       => null,
        ]), new SignatureDataFormatter(StatusDetailPaymentResponse::encodeForSignature()));

        /** @var mixed[] $data */
        $data = $response->getData();

        return StatusDetailPaymentResponse::createFromResponseData($data);
    }

}
