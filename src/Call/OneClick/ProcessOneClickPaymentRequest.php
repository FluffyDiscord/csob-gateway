<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call\OneClick;

use SlevomatCsobGateway\AdditionalData\Fingerprint;
use SlevomatCsobGateway\Call\ActionsPaymentResponse;
use SlevomatCsobGateway\Crypto\SignatureDataFormatter;
use SlevomatCsobGateway\EncodeHelper;
use SlevomatCsobGateway\Validator;
use function array_filter;

class ProcessOneClickPaymentRequest
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
     * @var \SlevomatCsobGateway\AdditionalData\Fingerprint|null
     */
    private $fingerprint;

    public function __construct(string $merchantId, string $payId, ?Fingerprint $fingerprint = null)
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
        $requestData = array_filter([
            'merchantId'  => $this->merchantId,
            'payId'       => $this->payId,
            'fingerprint' => ($nullsafeVariable1 = $this->fingerprint) ? $nullsafeVariable1->encode() : null,
        ], EncodeHelper::filterValueCallback() === null ? function ($value, $key): bool {
            return !empty($value);
        } : EncodeHelper::filterValueCallback(), EncodeHelper::filterValueCallback() === null ? ARRAY_FILTER_USE_BOTH : 0);

        $response = $apiClient->post('oneclick/process', $requestData, new SignatureDataFormatter([
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
