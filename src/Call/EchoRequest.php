<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call;

use SlevomatCsobGateway\Crypto\SignatureDataFormatter;

class EchoRequest
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
        $response = $apiClient->get('echo/{merchantId}/{dttm}/{signature}', [
            'merchantId' => $this->merchantId,
        ], new SignatureDataFormatter([
            'merchantId' => null,
            'dttm'       => null,
        ]), new SignatureDataFormatter(EchoResponse::encodeForSignature()));

        /** @var mixed[] $data */
        $data = $response->getData();

        return EchoResponse::createFromResponseData($data);
    }

}
