<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call\ApplePay;

use SlevomatCsobGateway\AdditionalData\Fingerprint;
use SlevomatCsobGateway\Call\ActionsPaymentResponse;
use SlevomatCsobGateway\Crypto\SignatureDataFormatter;
use SlevomatCsobGateway\Validator;

class ProcessApplePayRequest
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
     * @var \SlevomatCsobGateway\AdditionalData\Fingerprint
     */
    private $fingerprint;

    public function __construct(string $merchantId, string $payId, Fingerprint $fingerprint)
    {
        $this->merchantId = $merchantId;
        $this->payId = $payId;
        $this->fingerprint = $fingerprint;
        Validator::checkPayId($payId);
    }

    /**
     * @param \SlevomatCsobGateway\Api\ApiClient $apiClient
     */
    public function send($apiClient): ActionsPaymentResponse
    {
        $requestData = [
            'merchantId'  => $this->merchantId,
            'payId'       => $this->payId,
            'fingerprint' => $this->fingerprint->encode(),
        ];

        $response = $apiClient->post('applepay/process', $requestData, new SignatureDataFormatter([
            'merchantId'  => null,
            'payId'       => null,
            'dttm'        => null,
            'fingerprint' => Fingerprint::encodeForSignature(),
        ]), new SignatureDataFormatter(ActionsPaymentResponse::encodeForSignature()));

        /** @var mixed[] $data */
        $data = $response->getData();

        return ActionsPaymentResponse::createFromResponseData($data);
    }

}
