<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call;

use DateTimeImmutable;
use InvalidArgumentException;
use SlevomatCsobGateway\AdditionalData\Customer;
use SlevomatCsobGateway\AdditionalData\Order;
use SlevomatCsobGateway\Api\HttpMethod;
use SlevomatCsobGateway\Cart;
use SlevomatCsobGateway\Crypto\SignatureDataFormatter;
use SlevomatCsobGateway\EncodeHelper;
use SlevomatCsobGateway\Language;
use SlevomatCsobGateway\Validator;
use function array_filter;
use function base64_encode;
use function sprintf;

class InitPaymentRequest
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
    private $payOperation;
    /**
     * @var string
     */
    private $payMethod;
    /**
     * @var bool
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
     * @var \SlevomatCsobGateway\Cart
     */
    private $cart;
    /**
     * @var \SlevomatCsobGateway\AdditionalData\Customer|null
     */
    private $customer;
    /**
     * @var \SlevomatCsobGateway\AdditionalData\Order|null
     */
    private $order;
    /**
     * @var string|null
     */
    private $merchantData;
    /**
     * @var string|null
     */
    private $customerId;
    /**
     * @var string
     */
    private $language;
    /**
     * @var int|null
     */
    private $ttlSec;
    /**
     * @var int|null
     */
    private $logoVersion;
    /**
     * @var int|null
     */
    private $colorSchemeVersion;
    /**
     * @var \DateTimeImmutable|null
     */
    private $customExpiry;

    public function __construct(string $merchantId, string $orderId, string $payOperation, string $payMethod, bool $closePayment, string $returnUrl, string $returnMethod, Cart $cart, ?Customer $customer, ?Order $order, ?string $merchantData, ?string $customerId, string $language, ?int $ttlSec = null, ?int $logoVersion = null, ?int $colorSchemeVersion = null, ?DateTimeImmutable $customExpiry = null)
    {
        $this->merchantId = $merchantId;
        $this->orderId = $orderId;
        $this->payOperation = $payOperation;
        $this->payMethod = $payMethod;
        $this->closePayment = $closePayment;
        $this->returnUrl = $returnUrl;
        $this->returnMethod = $returnMethod;
        $this->cart = $cart;
        $this->customer = $customer;
        $this->order = $order;
        $this->merchantData = $merchantData;
        $this->customerId = $customerId;
        $this->language = $language;
        $this->ttlSec = $ttlSec;
        $this->logoVersion = $logoVersion;
        $this->colorSchemeVersion = $colorSchemeVersion;
        $this->customExpiry = $customExpiry;
        Validator::checkOrderId($this->orderId);
        Validator::checkReturnUrl($this->returnUrl);
        Validator::checkReturnMethod($this->returnMethod);
        if ($this->merchantData !== null) {
            Validator::checkMerchantData($this->merchantData);
        }
        if ($this->customerId !== null) {
            Validator::checkCustomerId($this->customerId);
        }
        if ($this->ttlSec !== null) {
            Validator::checkTtlSec($this->ttlSec);
        }
        if ($this->payOperation === PayOperation::CUSTOM_PAYMENT && $this->customExpiry === null) {
            throw new InvalidArgumentException(sprintf('Custom expiry parameter is required for custom payment.'));
        }
    }

    /**
     * @param \SlevomatCsobGateway\Api\ApiClient $apiClient
     */
    public function send($apiClient): InitPaymentResponse
    {
        $price = $this->cart->getCurrentPrice();

        $requestData = array_filter([
            'merchantId'         => $this->merchantId,
            'orderNo'            => $this->orderId,
            'payOperation'       => $this->payOperation,
            'payMethod'          => $this->payMethod,
            'totalAmount'        => $price->getAmount(),
            'currency'           => $price->getCurrency(),
            'closePayment'       => $this->closePayment,
            'returnUrl'          => $this->returnUrl,
            'returnMethod'       => $this->returnMethod,
            'cart'               => $this->cart->encode(),
            'customer'           => ($nullsafeVariable1 = $this->customer) ? $nullsafeVariable1->encode() : null,
            'order'              => ($nullsafeVariable2 = $this->order) ? $nullsafeVariable2->encode() : null,
            'merchantData'       => $this->merchantData !== null ? base64_encode($this->merchantData) : null,
            'customerId'         => $this->customerId,
            'language'           => $this->language,
            'ttlSec'             => $this->ttlSec,
            'logoVersion'        => $this->logoVersion,
            'colorSchemeVersion' => $this->colorSchemeVersion,
            'customExpiry'       => ($nullsafeVariable3 = $this->customExpiry) ? $nullsafeVariable3->format('YmdHis') : null,
        ], EncodeHelper::filterValueCallback() === null ? function ($value, $key): bool {
            return !empty($value);
        } : EncodeHelper::filterValueCallback(), EncodeHelper::filterValueCallback() === null ? ARRAY_FILTER_USE_BOTH : 0);

        $response = $apiClient->post('payment/init', $requestData, new SignatureDataFormatter([
            'merchantId'         => null,
            'orderNo'            => null,
            'dttm'               => null,
            'payOperation'       => null,
            'payMethod'          => null,
            'totalAmount'        => null,
            'currency'           => null,
            'closePayment'       => null,
            'returnUrl'          => null,
            'returnMethod'       => null,
            'cart'               => Cart::encodeForSignature(),
            'customer'           => Customer::encodeForSignature(),
            'order'              => Order::encodeForSignature(),
            'merchantData'       => null,
            'customerId'         => null,
            'language'           => null,
            'ttlSec'             => null,
            'logoVersion'        => null,
            'colorSchemeVersion' => null,
            'customExpiry'       => null,
        ]), new SignatureDataFormatter(InitPaymentResponse::encodeForSignature()));

        /** @var mixed[] $data */
        $data = $response->getData();

        return InitPaymentResponse::createFromResponseData($data);
    }

}
