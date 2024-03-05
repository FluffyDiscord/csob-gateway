<?php declare(strict_types=1);

namespace SlevomatCsobGateway;

use SlevomatCsobGateway\Call\ApplePay\EchoApplePayRequest;
use SlevomatCsobGateway\Call\ApplePay\InitApplePayRequest;
use SlevomatCsobGateway\Call\ApplePay\ProcessApplePayRequest;
use SlevomatCsobGateway\Call\Button\PaymentButtonRequest;
use SlevomatCsobGateway\Call\ClosePaymentRequest;
use SlevomatCsobGateway\Call\EchoCustomerRequest;
use SlevomatCsobGateway\Call\EchoRequest;
use SlevomatCsobGateway\Call\GooglePay\EchoGooglePayRequest;
use SlevomatCsobGateway\Call\GooglePay\InitGooglePayRequest;
use SlevomatCsobGateway\Call\GooglePay\ProcessGooglePayRequest;
use SlevomatCsobGateway\Call\InitPaymentRequest;
use SlevomatCsobGateway\Call\MallPay\CancelMallPayRequest;
use SlevomatCsobGateway\Call\MallPay\InitMallPayRequest;
use SlevomatCsobGateway\Call\MallPay\LogisticsMallPayRequest;
use SlevomatCsobGateway\Call\MallPay\RefundMallPayRequest;
use SlevomatCsobGateway\Call\OneClick\EchoOneClickRequest;
use SlevomatCsobGateway\Call\OneClick\InitOneClickPaymentRequest;
use SlevomatCsobGateway\Call\OneClick\ProcessOneClickPaymentRequest;
use SlevomatCsobGateway\Call\PaymentStatusRequest;
use SlevomatCsobGateway\Call\PostEchoRequest;
use SlevomatCsobGateway\Call\ProcessPaymentRequest;
use SlevomatCsobGateway\Call\ReceivePaymentRequest;
use SlevomatCsobGateway\Call\RefundPaymentRequest;
use SlevomatCsobGateway\Call\ReversePaymentRequest;
use SlevomatCsobGateway\MallPay\OrderItemReference;

class RequestFactory
{

    /**
     * @var string
     */
    private $merchantId;

    public function __construct(string $merchantId)
    {
        $this->merchantId = $merchantId;
    }

    /**
     * @param string $orderId
     * @param string $payOperation
     * @param string $payMethod
     * @param bool $closePayment
     * @param string $returnUrl
     * @param string $returnMethod
     * @param \SlevomatCsobGateway\Cart $cart
     * @param \SlevomatCsobGateway\AdditionalData\Customer|null $customer
     * @param \SlevomatCsobGateway\AdditionalData\Order|null $order
     * @param string|null $merchantData
     * @param string|null $customerId
     * @param string $language
     * @param int|null $ttlSec
     * @param int|null $logoVersion
     * @param int|null $colorSchemeVersion
     * @param \DateTimeImmutable|null $customExpiry
     */
    public function createInitPayment($orderId, $payOperation, $payMethod, $closePayment, $returnUrl, $returnMethod, $cart, $customer, $order, $merchantData, $customerId, $language, $ttlSec = null, $logoVersion = null, $colorSchemeVersion = null, $customExpiry = null): InitPaymentRequest
    {
        return new InitPaymentRequest($this->merchantId, $orderId, $payOperation, $payMethod, $closePayment, $returnUrl, $returnMethod, $cart, $customer, $order, $merchantData, $customerId, $language, $ttlSec, $logoVersion, $colorSchemeVersion, $customExpiry);
    }

    /**
     * @param string $payId
     */
    public function createProcessPayment($payId): ProcessPaymentRequest
    {
        return new ProcessPaymentRequest($this->merchantId, $payId);
    }

    /**
     * @param string $payId
     */
    public function createPaymentStatus($payId): PaymentStatusRequest
    {
        return new PaymentStatusRequest($this->merchantId, $payId);
    }

    /**
     * @param string $payId
     */
    public function createReversePayment($payId): ReversePaymentRequest
    {
        return new ReversePaymentRequest($this->merchantId, $payId);
    }

    /**
     * @param string $payId
     * @param int|null $totalAmount
     */
    public function createClosePayment($payId, $totalAmount = null): ClosePaymentRequest
    {
        return new ClosePaymentRequest($this->merchantId, $payId, $totalAmount);
    }

    /**
     * @param string $payId
     * @param int|null $amount
     */
    public function createRefundPayment($payId, $amount = null): RefundPaymentRequest
    {
        return new RefundPaymentRequest($this->merchantId, $payId, $amount);
    }

    public function createEchoRequest(): EchoRequest
    {
        return new EchoRequest($this->merchantId);
    }

    public function createPostEchoRequest(): PostEchoRequest
    {
        return new PostEchoRequest($this->merchantId);
    }

    /**
     * @param string $customerId
     */
    public function createEchoCustomer($customerId): EchoCustomerRequest
    {
        return new EchoCustomerRequest($this->merchantId, $customerId);
    }

    public function createReceivePaymentRequest(): ReceivePaymentRequest
    {
        return new ReceivePaymentRequest();
    }

    /**
     * @param string $origPayId
     * @param string $orderId
     * @param string|null $clientIp
     * @param \SlevomatCsobGateway\Price|null $price
     * @param bool|null $closePayment
     * @param string $returnUrl
     * @param string $returnMethod
     * @param \SlevomatCsobGateway\AdditionalData\Customer|null $customer
     * @param \SlevomatCsobGateway\AdditionalData\Order|null $order
     * @param bool|null $clientInitiated
     * @param bool|null $sdkUsed
     * @param string|null $merchantData
     * @param Language::*|null $language
     * @param int|null $ttlSec
     */
    public function createOneclickInitPayment($origPayId, $orderId, $clientIp, $price, $closePayment, $returnUrl, $returnMethod, $customer = null, $order = null, $clientInitiated = null, $sdkUsed = null, $merchantData = null, ?string $language = null, ?int $ttlSec = null): InitOneClickPaymentRequest
    {
        return new InitOneClickPaymentRequest($this->merchantId, $origPayId, $orderId, $clientIp, $price, $closePayment, $returnUrl, $returnMethod, $customer, $order, $clientInitiated, $sdkUsed, $merchantData, $language, $ttlSec);
    }

    /**
     * @param string $payId
     * @param \SlevomatCsobGateway\AdditionalData\Fingerprint|null $fingerprint
     */
    public function createOneclickProcessPayment($payId, $fingerprint = null): ProcessOneClickPaymentRequest
    {
        return new ProcessOneClickPaymentRequest($this->merchantId, $payId, $fingerprint);
    }

    /**
     * @param string $orderId
     * @param string $clientIp
     * @param \SlevomatCsobGateway\Price $totalPrice
     * @param string $returnUrl
     * @param string $returnMethod
     * @param string|null $brand
     * @param string|null $merchantData
     * @param string $language
     */
    public function createPaymentButtonRequest($orderId, $clientIp, $totalPrice, $returnUrl, $returnMethod, $brand, $merchantData, $language): PaymentButtonRequest
    {
        return new PaymentButtonRequest($this->merchantId, $orderId, $clientIp, $totalPrice, $returnUrl, $returnMethod, $brand, $merchantData, $language);
    }

    public function createApplePayEchoRequest(): EchoApplePayRequest
    {
        return new EchoApplePayRequest($this->merchantId);
    }

    /**
     * @param mixed[] $payload Complete payload from Apple Pay JS API, containing paymentData.
     * @param string $orderId
     * @param string $clientIp
     * @param \SlevomatCsobGateway\Price $totalPrice
     * @param bool $closePayment
     * @param string $returnUrl
     * @param string $returnMethod
     * @param \SlevomatCsobGateway\AdditionalData\Customer|null $customer
     * @param \SlevomatCsobGateway\AdditionalData\Order|null $order
     * @param bool|null $sdkUsed
     * @param string|null $merchantData
     * @param Language::*|null $language
     * @param int|null $ttlSec
     */
    public function createApplePayInitRequest($orderId, $clientIp, $totalPrice, $closePayment, $payload, $returnUrl, $returnMethod, $customer = null, $order = null, $sdkUsed = null, $merchantData = null, $language = null, $ttlSec = null): InitApplePayRequest
    {
        return new InitApplePayRequest($this->merchantId, $orderId, $clientIp, $totalPrice, $closePayment, $payload, $returnUrl, $returnMethod, $customer, $order, $sdkUsed, $merchantData, $language, $ttlSec);
    }

    /**
     * @param string $payId
     * @param \SlevomatCsobGateway\AdditionalData\Fingerprint $fingerprint
     */
    public function createApplePayProcessRequest($payId, $fingerprint): ProcessApplePayRequest
    {
        return new ProcessApplePayRequest($this->merchantId, $payId, $fingerprint);
    }

    /**
     * @param string $payId
     */
    public function createOneClickEchoRequest($payId): EchoOneClickRequest
    {
        return new EchoOneClickRequest($this->merchantId, $payId);
    }

    /**
     * @param string $orderId
     * @param \SlevomatCsobGateway\MallPay\Customer $customer
     * @param \SlevomatCsobGateway\MallPay\Order $order
     * @param bool $agreeTC
     * @param string $clientIp
     * @param string $returnMethod
     * @param string $returnUrl
     * @param string|null $merchantData
     * @param int|null $ttlSec
     */
    public function createMallPayInitRequest($orderId, $customer, $order, $agreeTC, $clientIp, $returnMethod, $returnUrl, $merchantData, $ttlSec): InitMallPayRequest
    {
        return new InitMallPayRequest($this->merchantId, $orderId, $customer, $order, $agreeTC, $clientIp, $returnMethod, $returnUrl, $merchantData, $ttlSec);
    }

    /**
     * @param string $payId
     * @param string $event
     * @param \DateTimeImmutable $date
     * @param \SlevomatCsobGateway\MallPay\OrderReference $fulfilled
     * @param \SlevomatCsobGateway\MallPay\OrderReference|null $cancelled
     * @param string|null $deliveryTrackingNumber
     */
    public function createMallPayLogisticsRequest($payId, $event, $date, $fulfilled, $cancelled, $deliveryTrackingNumber): LogisticsMallPayRequest
    {
        return new LogisticsMallPayRequest($this->merchantId, $payId, $event, $date, $fulfilled, $cancelled, $deliveryTrackingNumber);
    }

    /**
     * @param string $payId
     * @param string $reason
     */
    public function createMallPayCancelRequest($payId, $reason): CancelMallPayRequest
    {
        return new CancelMallPayRequest($this->merchantId, $payId, $reason);
    }

    /**
     * @param OrderItemReference[] $refundedItems
     * @param string $payId
     * @param int|null $amount
     */
    public function createMallPayRefundRequest($payId, $amount, $refundedItems): RefundMallPayRequest
    {
        return new RefundMallPayRequest($this->merchantId, $payId, $amount, $refundedItems);
    }

    public function createGooglePayEchoRequest(): EchoGooglePayRequest
    {
        return new EchoGooglePayRequest($this->merchantId);
    }

    /**
     * @param mixed[] $payload Complete payload from Google Pay JS API, containing paymentMethodData.tokenizationData.token
     * @param string $orderId
     * @param string $clientIp
     * @param \SlevomatCsobGateway\Price $totalPrice
     * @param bool|null $closePayment
     * @param string $returnUrl
     * @param string $returnMethod
     * @param \SlevomatCsobGateway\AdditionalData\Customer|null $customer
     * @param \SlevomatCsobGateway\AdditionalData\Order|null $order
     * @param bool|null $sdkUsed
     * @param string|null $merchantData
     * @param Language::*|null $language
     * @param int|null $ttlSec
     */
    public function createGooglePayInitRequest($orderId, $clientIp, $totalPrice, $closePayment, $payload, $returnUrl, $returnMethod, $customer = null, $order = null, $sdkUsed = null, $merchantData = null, $language = null, $ttlSec = null): InitGooglePayRequest
    {
        return new InitGooglePayRequest($this->merchantId, $orderId, $clientIp, $totalPrice, $closePayment, $payload, $returnUrl, $returnMethod, $customer, $order, $sdkUsed, $merchantData, $language, $ttlSec);
    }

    /**
     * @param string $payId
     * @param \SlevomatCsobGateway\AdditionalData\Fingerprint $fingerprint
     */
    public function createGooglePayProcessRequest($payId, $fingerprint): ProcessGooglePayRequest
    {
        return new ProcessGooglePayRequest($this->merchantId, $payId, $fingerprint);
    }

}
