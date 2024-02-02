<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call;

use SlevomatCsobGateway\Crypto\SignatureDataFormatter;
use SlevomatCsobGateway\EncodeHelper;
use SlevomatCsobGateway\Validator;
use function array_filter;

class RefundPaymentRequest
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
    private $amount;

    public function __construct(string $merchantId, string $payId, ?int $amount = null)
    {
        $this->merchantId = $merchantId;
        $this->payId = $payId;
        $this->amount = $amount;
        Validator::checkPayId($payId);
    }

    /**
     * @param \SlevomatCsobGateway\Api\ApiClient $apiClient
     */
    public function send($apiClient): AuthCodeStatusDetailPaymentResponse
    {
        $requestData = array_filter([
            'merchantId' => $this->merchantId,
            'payId'      => $this->payId,
            'amount'     => $this->amount,
        ], EncodeHelper::filterValueCallback() === null ? function ($value, $key): bool {
            return !empty($value);
        } : EncodeHelper::filterValueCallback(), EncodeHelper::filterValueCallback() === null ? ARRAY_FILTER_USE_BOTH : 0);

        $response = $apiClient->put('payment/refund', $requestData, new SignatureDataFormatter([
            'merchantId' => null,
            'payId'      => null,
            'dttm'       => null,
            'amount'     => null,
        ]), new SignatureDataFormatter(AuthCodeStatusDetailPaymentResponse::encodeForSignature()));

        /** @var mixed[] $data */
        $data = $response->getData();

        return AuthCodeStatusDetailPaymentResponse::createFromResponseData($data);
    }

}
