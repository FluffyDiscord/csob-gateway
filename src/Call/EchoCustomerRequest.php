<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call;

use SlevomatCsobGateway\Crypto\SignatureDataFormatter;
use SlevomatCsobGateway\Validator;

class EchoCustomerRequest
{

    /**
     * @var string
     */
    private $merchantId;
    /**
     * @var string
     */
    private $customerId;

    public function __construct(string $merchantId, string $customerId)
    {
        $this->merchantId = $merchantId;
        $this->customerId = $customerId;
        Validator::checkCustomerId($customerId);
    }

    /**
     * @param \SlevomatCsobGateway\Api\ApiClient $apiClient
     */
    public function send($apiClient): EchoCustomerResponse
    {
        $response = $apiClient->get('echo/customer', [
            'merchantId' => $this->merchantId,
            'customerId' => $this->customerId,
        ], new SignatureDataFormatter([
            'merchantId' => null,
            'customerId' => null,
            'dttm'       => null,
        ]), new SignatureDataFormatter(EchoCustomerResponse::encodeForSignature()));

        /** @var mixed[] $data */
        $data = $response->getData();

        return EchoCustomerResponse::createFromResponseData($data);
    }

}
