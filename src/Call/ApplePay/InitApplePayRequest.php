<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call\ApplePay;

use SlevomatCsobGateway\AdditionalData\Customer;
use SlevomatCsobGateway\AdditionalData\Order;
use SlevomatCsobGateway\Call\ActionsPaymentResponse;
use SlevomatCsobGateway\Call\InvalidJsonPayloadException;
use SlevomatCsobGateway\Crypto\SignatureDataFormatter;
use SlevomatCsobGateway\EncodeHelper;
use SlevomatCsobGateway\Language;
use SlevomatCsobGateway\Price;
use SlevomatCsobGateway\Validator;
use function array_filter;
use function base64_encode;
use function json_encode;
use function json_last_error;
use function json_last_error_msg;
use const JSON_ERROR_NONE;
use const JSON_UNESCAPED_SLASHES;
use const JSON_UNESCAPED_UNICODE;

class InitApplePayRequest
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
     * @var bool
     */
    private $closePayment;
    /**
     * @var mixed[]
     */
    private $payload;
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

    /**
     * @param mixed[] $payload Complete payload from Apple Pay JS API, containing paymentData.
     */
    public function __construct(string $merchantId, string $orderId, string $clientIp, Price $totalPrice, bool $closePayment, array $payload, string $returnUrl, string $returnMethod, ?Customer $customer = null, ?Order $order = null, ?bool $sdkUsed = null, ?string $merchantData = null, ?string $language = null, ?int $ttlSec = null)
    {
        $this->merchantId = $merchantId;
        $this->orderId = $orderId;
        $this->clientIp = $clientIp;
        $this->totalPrice = $totalPrice;
        $this->closePayment = $closePayment;
        $this->payload = $payload;
        $this->returnUrl = $returnUrl;
        $this->returnMethod = $returnMethod;
        $this->customer = $customer;
        $this->order = $order;
        $this->sdkUsed = $sdkUsed;
        $this->merchantData = $merchantData;
        $this->language = $language;
        $this->ttlSec = $ttlSec;
        Validator::checkOrderId($this->orderId);
        Validator::checkReturnUrl($this->returnUrl);
        Validator::checkReturnMethod($this->returnMethod);
        if ($this->merchantData !== null) {
            Validator::checkMerchantData($this->merchantData);
        }
        if ($this->ttlSec !== null) {
            Validator::checkTtlSec($this->ttlSec);
        }
    }

    /**
     * @param \SlevomatCsobGateway\Api\ApiClient $apiClient
     */
    public function send($apiClient): ActionsPaymentResponse
    {
        $payloadData = $this->payload['paymentData'] ?? null;
        if ($payloadData === null) {
            throw new InvalidJsonPayloadException('Missing `paymentData` in ApplePay payload.');
        }
        $payloadData = json_encode($payloadData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $error = json_last_error();
        if ($error !== JSON_ERROR_NONE) {
            throw new InvalidJsonPayloadException(json_last_error_msg(), $error);
        }
        $payloadData = base64_encode((string)$payloadData);

        $requestData = array_filter([
            'merchantId'   => $this->merchantId,
            'orderNo'      => $this->orderId,
            'clientIp'     => $this->clientIp,
            'totalAmount'  => $this->totalPrice->getAmount(),
            'currency'     => $this->totalPrice->getCurrency(),
            'closePayment' => $this->closePayment,
            'payload'      => $payloadData,
            'returnUrl'    => $this->returnUrl,
            'returnMethod' => $this->returnMethod,
            'customer'     => ($nullsafeVariable1 = $this->customer) ? $nullsafeVariable1->encode() : null,
            'order'        => ($nullsafeVariable2 = $this->order) ? $nullsafeVariable2->encode() : null,
            'sdkUsed'      => $this->sdkUsed,
            'merchantData' => $this->merchantData !== null ? base64_encode($this->merchantData) : null,
            'language'     => $this->language,
            'ttlSec'       => $this->ttlSec,
        ], EncodeHelper::filterValueCallback() === null ? function ($value, $key): bool {
            return !empty($value);
        } : EncodeHelper::filterValueCallback(), EncodeHelper::filterValueCallback() === null ? ARRAY_FILTER_USE_BOTH : 0);

        $response = $apiClient->post('applepay/init', $requestData, new SignatureDataFormatter([
            'merchantId'   => null,
            'orderNo'      => null,
            'dttm'         => null,
            'clientIp'     => null,
            'totalAmount'  => null,
            'currency'     => null,
            'closePayment' => null,
            'payload'      => null,
            'returnUrl'    => null,
            'returnMethod' => null,
            'customer'     => Customer::encodeForSignature(),
            'order'        => Order::encodeForSignature(),
            'sdkUsed'      => null,
            'merchantData' => null,
            'language'     => null,
            'ttlSec'       => null,
        ]), new SignatureDataFormatter(ActionsPaymentResponse::encodeForSignature()));

        /** @var mixed[] $data */
        $data = $response->getData();

        return ActionsPaymentResponse::createFromResponseData($data);
    }

}
