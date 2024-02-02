<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call\MallPay;

use InvalidArgumentException;
use SlevomatCsobGateway\Api\HttpMethod;
use SlevomatCsobGateway\Crypto\SignatureDataFormatter;
use SlevomatCsobGateway\EncodeHelper;
use SlevomatCsobGateway\MallPay\AddressType;
use SlevomatCsobGateway\MallPay\Customer;
use SlevomatCsobGateway\MallPay\Order;
use SlevomatCsobGateway\Validator;
use function array_filter;
use function base64_encode;

class InitMallPayRequest
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
     * @var \SlevomatCsobGateway\MallPay\Customer
     */
    private $customer;
    /**
     * @var \SlevomatCsobGateway\MallPay\Order
     */
    private $order;
    /**
     * @var bool
     */
    private $agreeTC;
    /**
     * @var string
     */
    private $clientIp;
    /**
     * @var string
     */
    private $returnMethod;
    /**
     * @var string
     */
    private $returnUrl;
    /**
     * @var string|null
     */
    private $merchantData;
    /**
     * @var int|null
     */
    private $ttlSec;

    public function __construct(string $merchantId, string $orderId, Customer $customer, Order $order, bool $agreeTC, string $clientIp, string $returnMethod, string $returnUrl, ?string $merchantData = null, ?int $ttlSec = null)
    {
        $this->merchantId = $merchantId;
        $this->orderId = $orderId;
        $this->customer = $customer;
        $this->order = $order;
        $this->agreeTC = $agreeTC;
        $this->clientIp = $clientIp;
        $this->returnMethod = $returnMethod;
        $this->returnUrl = $returnUrl;
        $this->merchantData = $merchantData;
        $this->ttlSec = $ttlSec;
        Validator::checkOrderId($orderId);
        Validator::checkReturnMethod($returnMethod);
        if ($merchantData !== null) {
            Validator::checkMerchantData($merchantData);
        }
        if ($ttlSec !== null) {
            Validator::checkMallPayTtlSec($ttlSec);
        }
        if ($order->getItems() === []) {
            throw new InvalidArgumentException('Order has no items.');
        }
        $hasBillingAddress = false;
        foreach ($order->getAddresses() as $address) {
            if ($address->getAddressType() === AddressType::BILLING) {
                $hasBillingAddress = true;
                break;
            }
        }
        if (!$hasBillingAddress) {
            throw new InvalidArgumentException('Order doesnt have billing address.');
        }
        if ($returnMethod === HttpMethod::PUT) {
            throw new InvalidArgumentException('Unsupported return method PUT.');
        }
    }

    /**
     * @param \SlevomatCsobGateway\Api\ApiClient $apiClient
     */
    public function send($apiClient): InitMallPayResponse
    {
        $requestData = array_filter([
            'merchantId'   => $this->merchantId,
            'orderNo'      => $this->orderId,
            'customer'     => $this->customer->encode(),
            'order'        => $this->order->encode(),
            'agreeTC'      => $this->agreeTC,
            'clientIp'     => $this->clientIp,
            'returnUrl'    => $this->returnUrl,
            'returnMethod' => $this->returnMethod,
            'merchantData' => $this->merchantData !== null ? base64_encode($this->merchantData) : null,
            'ttlSec'       => $this->ttlSec,
        ], EncodeHelper::filterValueCallback() === null ? function ($value, $key): bool {
            return !empty($value);
        } : EncodeHelper::filterValueCallback(), EncodeHelper::filterValueCallback() === null ? ARRAY_FILTER_USE_BOTH : 0);

        $response = $apiClient->post('mallpay/init', $requestData, new SignatureDataFormatter([
            'merchantId'   => null,
            'orderNo'      => null,
            'customer'     => Customer::encodeForSignature(),
            'order'        => Order::encodeForSignature(),
            'agreeTC'      => null,
            'dttm'         => null,
            'clientIp'     => null,
            'returnUrl'    => null,
            'returnMethod' => null,
            'merchantData' => null,
            'ttlSec'       => null,
        ]), new SignatureDataFormatter(InitMallPayResponse::encodeForSignature()));

        /** @var mixed[] $data */
        $data = $response->getData();

        return InitMallPayResponse::createFromResponseData($data);
    }

}
