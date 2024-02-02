<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call\Button;

use SlevomatCsobGateway\Api\HttpMethod;
use SlevomatCsobGateway\Crypto\SignatureDataFormatter;
use SlevomatCsobGateway\EncodeHelper;
use SlevomatCsobGateway\Language;
use SlevomatCsobGateway\Price;
use SlevomatCsobGateway\Validator;
use function array_filter;
use function base64_encode;

class PaymentButtonRequest
{

    /**
     * @var string
     */
    private $merchantId;
    /**
     * @var string
     */
    private $orderId;
    /**
     * @var string
     */
    private $clientIp;
    /**
     * @var \SlevomatCsobGateway\Price
     */
    private $totalPrice;
    /**
     * @var string
     */
    private $returnUrl;
    /**
     * @var string
     */
    private $returnMethod;
    /**
     * @var string|null
     */
    private $brand;
    /**
     * @var string|null
     */
    private $merchantData;
    /**
     * @var string
     */
    private $language;

    public function __construct(string $merchantId, string $orderId, string $clientIp, Price $totalPrice, string $returnUrl, string $returnMethod, ?string $brand, ?string $merchantData, string $language)
    {
        $this->merchantId = $merchantId;
        $this->orderId = $orderId;
        $this->clientIp = $clientIp;
        $this->totalPrice = $totalPrice;
        $this->returnUrl = $returnUrl;
        $this->returnMethod = $returnMethod;
        $this->brand = $brand;
        $this->merchantData = $merchantData;
        $this->language = $language;
        Validator::checkReturnUrl($this->returnUrl);
        Validator::checkReturnMethod($this->returnMethod);
        if ($this->merchantData !== null) {
            Validator::checkMerchantData($this->merchantData);
        }
    }

    /**
     * @param \SlevomatCsobGateway\Api\ApiClient $apiClient
     */
    public function send($apiClient): PaymentButtonResponse
    {
        $requestData = array_filter([
            'merchantId'   => $this->merchantId,
            'orderNo'      => $this->orderId,
            'clientIp'     => $this->clientIp,
            'totalAmount'  => $this->totalPrice->getAmount(),
            'currency'     => $this->totalPrice->getCurrency(),
            'returnUrl'    => $this->returnUrl,
            'returnMethod' => $this->returnMethod,
            'brand'        => ($nullsafeVariable1 = $this->brand) ? $nullsafeVariable1 : null,
            'merchantData' => $this->merchantData !== null ? base64_encode($this->merchantData) : null,
            'language'     => $this->language,
        ], EncodeHelper::filterValueCallback() === null ? function ($value, $key): bool {
            return !empty($value);
        } : EncodeHelper::filterValueCallback(), EncodeHelper::filterValueCallback() === null ? ARRAY_FILTER_USE_BOTH : 0);

        $response = $apiClient->post('button/init', $requestData, new SignatureDataFormatter([
            'merchantId'   => null,
            'orderNo'      => null,
            'dttm'         => null,
            'clientIp'     => null,
            'totalAmount'  => null,
            'currency'     => null,
            'returnUrl'    => null,
            'returnMethod' => null,
            'brand'        => null,
            'merchantData' => null,
            'language'     => null,
        ]), new SignatureDataFormatter(PaymentButtonResponse::encodeForSignature()));

        /** @var mixed[] $data */
        $data = $response->getData();

        return PaymentButtonResponse::createFromResponseData($data);
    }

}
