<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call\GooglePay;

use SlevomatCsobGateway\Crypto\SignatureDataFormatter;

class EchoGooglePayRequest
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
    public function send($apiClient): EchoGooglePayResponse
    {
        $requestData = [
            'merchantId' => $this->merchantId,
        ];

        $response = $apiClient->post('googlepay/echo', $requestData, new SignatureDataFormatter([
            'merchantId' => null,
            'dttm'       => null,
        ]), new SignatureDataFormatter(EchoGooglePayResponse::encodeForSignature()));

        /** @var mixed[] $data */
        $data = $response->getData();

        return EchoGooglePayResponse::createFromResponseData($data);
    }

}
