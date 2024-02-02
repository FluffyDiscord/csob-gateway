<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call\OneClick;

use SlevomatCsobGateway\Crypto\SignatureDataFormatter;

class EchoOneClickRequest
{

    /**
     * @var string
     */
    private $merchantId;
    /**
     * @var string
     */
    private $origPayId;

    public function __construct(string $merchantId, string $origPayId)
    {
        $this->merchantId = $merchantId;
        $this->origPayId = $origPayId;
    }

    /**
     * @param \SlevomatCsobGateway\Api\ApiClient $apiClient
     */
    public function send($apiClient): EchoOneClickResponse
    {
        $requestData = [
            'merchantId' => $this->merchantId,
            'origPayId'  => $this->origPayId,
        ];

        $response = $apiClient->post('oneclick/echo', $requestData, new SignatureDataFormatter([
            'merchantId' => null,
            'origPayId'  => null,
            'dttm'       => null,
        ]), new SignatureDataFormatter(EchoOneClickResponse::encodeForSignature()));

        /** @var mixed[] $data */
        $data = $response->getData();

        return EchoOneClickResponse::createFromResponseData($data);
    }

}
