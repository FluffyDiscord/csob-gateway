<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call\OneClick;

use SlevomatCsobGateway\AdditionalData\Customer;
use SlevomatCsobGateway\AdditionalData\Order;
use SlevomatCsobGateway\Call\ActionsPaymentResponse;
use SlevomatCsobGateway\Crypto\SignatureDataFormatter;
use SlevomatCsobGateway\EncodeHelper;
use SlevomatCsobGateway\Price;
use SlevomatCsobGateway\Validator;
use function array_filter;
use function base64_encode;

class InitOneClickPaymentRequest
{

    /**
     * @var string
     */
    private $merchantId;
    /**
     * @var string
     */
    private $origPayId;
    /**
     * @var string
     */
    private $orderId;
    /**
     * @var string|null
     */
    private $clientIp;
    /**
     * @var \SlevomatCsobGateway\Price|null
     */
    private $price;
    /**
     * @var bool|null
     */
    private $closePayment;
    /**
     * @var string
     */
    private $returnUrl;
    /**
     * @var string
     */
    private $returnMethod;
    /**
     * @var \SlevomatCsobGateway\AdditionalData\Customer|null
     */
    private $customer;
    /**
     * @var \SlevomatCsobGateway\AdditionalData\Order|null
     */
    private $order;
    /**
     * @var bool|null
     */
    private $clientInitiated;
    /**
     * @var bool|null
     */
    private $sdkUsed;
    /**
     * @var string|null
     */
    private $merchantData;
    /**
     * @var Language::*|null
     */
    private $language;
    /**
     * @var int|null
     */
    private $ttlSec;

    public function __construct(string $merchantId, string $origPayId, string $orderId, ?string $clientIp, ?Price $price, ?bool $closePayment, string $returnUrl, string $returnMethod, ?Customer $customer = null, ?Order $order = null, ?bool $clientInitiated = null, ?bool $sdkUsed = null, ?string $merchantData = null, ?string $language = null, ?int $ttlSec = null)
    {
        $this->merchantId = $merchantId;
        $this->origPayId = $origPayId;
        $this->orderId = $orderId;
        $this->clientIp = $clientIp;
        $this->price = $price;
        $this->closePayment = $closePayment;
        $this->returnUrl = $returnUrl;
        $this->returnMethod = $returnMethod;
        $this->customer = $customer;
        $this->order = $order;
        $this->clientInitiated = $clientInitiated;
        $this->sdkUsed = $sdkUsed;
        $this->merchantData = $merchantData;
        $this->language = $language;
        $this->ttlSec = $ttlSec;
        Validator::checkPayId($this->origPayId);
        Validator::checkOrderId($this->orderId);
        Validator::checkReturnUrl($this->returnUrl);
        Validator::checkReturnMethod($this->returnMethod);
        if ($this->merchantData !== null) {
            Validator::checkMerchantData($this->merchantData);
        }
    }

    /**
     * @param \SlevomatCsobGateway\Api\ApiClient $apiClient
     */
    public function send($apiClient): ActionsPaymentResponse
    {
        $requestData = array_filter([
            'merchantId'      => $this->merchantId,
            'origPayId'       => $this->origPayId,
            'orderNo'         => $this->orderId,
            'clientIp'        => $this->clientIp,
            'totalAmount'     => ($nullsafeVariable1 = $this->price) ? $nullsafeVariable1->getAmount() : null,
            'currency'        => (($nullsafeVariable2 = $this->price) ? $nullsafeVariable2->getCurrency() : null),
            'closePayment'    => $this->closePayment,
            'returnUrl'       => $this->returnUrl,
            'returnMethod'    => $this->returnMethod,
            'customer'        => ($nullsafeVariable3 = $this->customer) ? $nullsafeVariable3->encode() : null,
            'order'           => ($nullsafeVariable4 = $this->order) ? $nullsafeVariable4->encode() : null,
            'clientInitiated' => $this->clientInitiated,
            'sdkUsed'         => $this->sdkUsed,
            'merchantData'    => $this->merchantData !== null ? base64_encode($this->merchantData) : null,
            'language'        => $this->language,
            'ttlSec'          => $this->ttlSec,
        ], EncodeHelper::filterValueCallback() === null ? function ($value, $key): bool {
            return !empty($value);
        } : EncodeHelper::filterValueCallback(), EncodeHelper::filterValueCallback() === null ? ARRAY_FILTER_USE_BOTH : 0);

        $response = $apiClient->post('oneclick/init', $requestData, new SignatureDataFormatter([
            'merchantId'      => null,
            'origPayId'       => null,
            'orderNo'         => null,
            'dttm'            => null,
            'clientIp'        => null,
            'totalAmount'     => null,
            'currency'        => null,
            'closePayment'    => null,
            'returnUrl'       => null,
            'returnMethod'    => null,
            'customer'        => Customer::encodeForSignature(),
            'order'           => Order::encodeForSignature(),
            'clientInitiated' => null,
            'sdkUsed'         => null,
            'merchantData'    => null,
            'language'        => null,
            'ttlSec'          => null,
        ]), new SignatureDataFormatter(ActionsPaymentResponse::encodeForSignature()));

        /** @var mixed[] $data */
        $data = $response->getData();

        return ActionsPaymentResponse::createFromResponseData($data);
    }

}
