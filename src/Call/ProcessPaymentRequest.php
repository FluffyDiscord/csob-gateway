<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call;

use SlevomatCsobGateway\Api\InvalidPaymentException;
use SlevomatCsobGateway\Api\Response;
use SlevomatCsobGateway\Api\ResponseCode;
use SlevomatCsobGateway\Crypto\SignatureDataFormatter;
use SlevomatCsobGateway\Validator;

class ProcessPaymentRequest
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
    public function send($apiClient): ProcessPaymentResponse
    {
        $response = $apiClient->get('payment/process/{merchantId}/{payId}/{dttm}/{signature}', [
            'merchantId' => $this->merchantId,
            'payId'      => $this->payId,
        ], new SignatureDataFormatter([
            'merchantId' => null,
            'payId'      => null,
            'dttm'       => null,
        ]), new SignatureDataFormatter(ProcessPaymentResponse::encodeForSignature()), function (Response $response): void {
            // This handles edge case when provided payId is missing or already expired on gateway
            // In this case gateway responds with HTTP 200 and HTML content. Bad API.
            // See https://github.com/csob/paymentgateway/issues/135
            if ($response->getResponseCode() === ResponseCode::S200_OK) {
                throw new InvalidPaymentException($this, $response, $this->payId);
            }
        });

        /** @var string $gatewayLocationUrl */
        $gatewayLocationUrl = $response->getHeaders()['Location'];

        return new ProcessPaymentResponse($gatewayLocationUrl);
    }

}
