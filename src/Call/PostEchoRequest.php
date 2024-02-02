<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call;

use SlevomatCsobGateway\Crypto\SignatureDataFormatter;

class PostEchoRequest
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
    public function send($apiClient): EchoResponse
    {
        $response = $apiClient->post('echo', [
            'merchantId' => $this->merchantId,
        ], new SignatureDataFormatter([
            'merchantId' => null,
            'dttm'       => null,
        ]), new SignatureDataFormatter([
            'dttm'          => null,
            'resultCode'    => null,
            'resultMessage' => null,
        ]));

        /** @var mixed[] $data */
        $data = $response->getData();

        return EchoResponse::createFromResponseData($data);
    }

}
