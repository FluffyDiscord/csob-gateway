<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call;

use SlevomatCsobGateway\Crypto\SignatureDataFormatter;
use SlevomatCsobGateway\Validator;

class PaymentStatusRequest
{

    /**
     * @var string
     */
    private $merchantId;
    /**
     * @var string
     */
    private $payId;
    /** @var ResponseExtensionHandler[] */
    private $extensions = [];

    public function __construct(string $merchantId, string $payId)
    {
        $this->merchantId = $merchantId;
        $this->payId = $payId;
        Validator::checkPayId($payId);
    }

    /**
     * @param \SlevomatCsobGateway\Api\ApiClient $apiClient
     */
    public function send($apiClient): PaymentStatusResponse
    {
        $response = $apiClient->get('payment/status/{merchantId}/{payId}/{dttm}/{signature}', [
            'merchantId' => $this->merchantId,
            'payId'      => $this->payId,
        ], new SignatureDataFormatter([
            'merchantId' => null,
            'payId'      => null,
            'dttm'       => null,
        ]), new SignatureDataFormatter(PaymentStatusResponse::encodeForSignature()), null, $this->extensions);

        /** @var mixed[] $data */
        $data = $response->getData();
        $data['extensions'] = $response->getExtensions();

        return PaymentStatusResponse::createFromResponseData($data);
    }

    /**
     * @param string $name
     * @param \SlevomatCsobGateway\Call\ResponseExtensionHandler $extensionHandler
     */
    public function registerExtension($name, $extensionHandler): void
    {
        $this->extensions[$name] = $extensionHandler;
    }

}
