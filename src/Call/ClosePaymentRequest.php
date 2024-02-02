<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call;

use SlevomatCsobGateway\Crypto\SignatureDataFormatter;
use SlevomatCsobGateway\EncodeHelper;
use SlevomatCsobGateway\Validator;
use function array_filter;

class ClosePaymentRequest
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
     * @var int|null
     */
    private $totalAmount;

    public function __construct(string $merchantId, string $payId, ?int $totalAmount = null)
    {
        $this->merchantId = $merchantId;
        $this->payId = $payId;
        $this->totalAmount = $totalAmount;
        Validator::checkPayId($payId);
    }

    /**
     * @param \SlevomatCsobGateway\Api\ApiClient $apiClient
     */
    public function send($apiClient): AuthCodeStatusDetailPaymentResponse
    {
        $data = array_filter([
            'merchantId'  => $this->merchantId,
            'payId'       => $this->payId,
            'totalAmount' => $this->totalAmount,
        ], EncodeHelper::filterValueCallback() === null ? function ($value, $key): bool {
            return !empty($value);
        } : EncodeHelper::filterValueCallback(), EncodeHelper::filterValueCallback() === null ? ARRAY_FILTER_USE_BOTH : 0);

        $response = $apiClient->put('payment/close', $data, new SignatureDataFormatter([
            'merchantId'  => null,
            'payId'       => null,
            'dttm'        => null,
            'totalAmount' => null,
        ]), new SignatureDataFormatter(AuthCodeStatusDetailPaymentResponse::encodeForSignature()));

        /** @var mixed[] $data */
        $data = $response->getData();

        return AuthCodeStatusDetailPaymentResponse::createFromResponseData($data);
    }

}
