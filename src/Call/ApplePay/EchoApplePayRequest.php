<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call\ApplePay;

use SlevomatCsobGateway\Crypto\SignatureDataFormatter;

class EchoApplePayRequest
{

    /**
     * @var string
     */
    private $merchantId;

    public function __construct(string $merchantId)
    {
        $this->merchantId = $merchantId;
    }

    /**
     * @param \SlevomatCsobGateway\Api\ApiClient $apiClient
     */
    public function send($apiClient): EchoApplePayResponse
    {
        $requestData = [
            'merchantId' => $this->merchantId,
        ];

        $response = $apiClient->post('applepay/echo', $requestData, new SignatureDataFormatter([
            'merchantId' => null,
            'dttm'       => null,
        ]), new SignatureDataFormatter(EchoApplePayResponse::encodeForSignature()));

        /** @var mixed[] $data */
        $data = $response->getData();

        return EchoApplePayResponse::createFromResponseData($data);
    }

}
