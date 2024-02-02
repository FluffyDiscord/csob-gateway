<?php declare(strict_types=1);

namespace SlevomatCsobGateway;

use InvalidArgumentException;
use SlevomatCsobGateway\Api\HttpMethod;
use function base64_encode;
use function ctype_digit;
use function filter_var;
use function mb_strlen;
use function preg_match;
use function preg_quote;
use function preg_replace;
use function sprintf;
use function strlen;
use const FILTER_VALIDATE_EMAIL;
use const FILTER_VALIDATE_URL;

class Validator
{

    private const CART_ITEM_NAME_LENGTH_MAX = 20;
    private const CART_ITEM_DESCRIPTION_LENGTH_MAX = 40;

    private const ORDER_ID_LENGTH_MAX = 10;
    private const RETURN_URL_LENGTH_MAX = 300;
    private const DESCRIPTION_LENGTH_MAX = 255;
    private const MERCHANT_DATA_LENGTH_MAX = 255;
    private const CUSTOMER_ID_LENGTH_MAX = 50;
    private const PAY_ID_LENGTH_MAX = 15;

    private const TTL_SEC_MIN = 300;
    private const TTL_SEC_MAX = 1800;

    private const MALL_PAY_TTL_SEC_MIN = 600;
    private const MALL_PAY_TTL_SEC_MAX = 43200;

    /**
     * @param string $name
     */
    public static function checkCartItemName($name): void
    {
        self::checkWhitespaces($name);

        if (mb_strlen($name) > self::CART_ITEM_NAME_LENGTH_MAX) {
            throw new InvalidArgumentException(sprintf('Cart item name can have maximum of %d characters.', self::CART_ITEM_NAME_LENGTH_MAX));
        }
    }

    /**
     * @param string $description
     */
    public static function checkCartItemDescription($description): void
    {
        self::checkWhitespaces($description);

        if (mb_strlen($description) > self::CART_ITEM_DESCRIPTION_LENGTH_MAX) {
            throw new InvalidArgumentException(sprintf('Cart item description can have maximum of %d characters.', self::CART_ITEM_DESCRIPTION_LENGTH_MAX));
        }
    }

    /**
     * @param int $quantity
     */
    public static function checkCartItemQuantity($quantity): void
    {
        if ($quantity < 1) {
            throw new InvalidArgumentException(sprintf('Quantity must be greater than 0. %d given.', $quantity));
        }
    }

    /**
     * @param string $orderId
     */
    public static function checkOrderId($orderId): void
    {
        self::checkWhitespaces($orderId);

        if (!ctype_digit($orderId)) {
            throw new InvalidArgumentException(sprintf('OrderId must be numeric value. %s given.', $orderId));
        }

        if (strlen($orderId) > self::ORDER_ID_LENGTH_MAX) {
            throw new InvalidArgumentException(sprintf('OrderId can have maximum of %d characters.', self::ORDER_ID_LENGTH_MAX));
        }
    }

    /**
     * @param string $returnUrl
     */
    public static function checkReturnUrl($returnUrl): void
    {
        self::checkWhitespaces($returnUrl);

        if (mb_strlen($returnUrl) > self::RETURN_URL_LENGTH_MAX) {
            throw new InvalidArgumentException(sprintf('ReturnUrl can have maximum of %d characters.', self::RETURN_URL_LENGTH_MAX));
        }
    }

    /**
     * @param string $httpMethod
     */
    public static function checkReturnMethod($httpMethod): void
    {
        if ($httpMethod !== HttpMethod::POST && $httpMethod !== HttpMethod::GET) {
            throw new InvalidArgumentException(sprintf('Only %s or %s is allowed as returnMethod.', HttpMethod::POST, HttpMethod::GET));
        }
    }

    /**
     * @param string $description
     */
    public static function checkDescription($description): void
    {
        self::checkWhitespaces($description);

        if (mb_strlen($description) > self::DESCRIPTION_LENGTH_MAX) {
            throw new InvalidArgumentException(sprintf('Description can have maximum of %d characters.', self::DESCRIPTION_LENGTH_MAX));
        }
    }

    /**
     * @param string $merchantData
     */
    public static function checkMerchantData($merchantData): void
    {
        self::checkWhitespaces($merchantData);

        if (mb_strlen(base64_encode($merchantData)) > self::MERCHANT_DATA_LENGTH_MAX) {
            throw new InvalidArgumentException(sprintf('MerchantData can have maximum of %d characters in encoded state.', self::MERCHANT_DATA_LENGTH_MAX));
        }
    }

    /**
     * @param string $customerId
     */
    public static function checkCustomerId($customerId): void
    {
        self::checkWhitespaces($customerId);

        if (mb_strlen($customerId) > self::CUSTOMER_ID_LENGTH_MAX) {
            throw new InvalidArgumentException(sprintf('CustomerId can have maximum of %d characters.', self::CUSTOMER_ID_LENGTH_MAX));
        }
    }

    /**
     * @param string $payId
     */
    public static function checkPayId($payId): void
    {
        self::checkWhitespaces($payId);

        if (mb_strlen($payId) > self::PAY_ID_LENGTH_MAX) {
            throw new InvalidArgumentException(sprintf('PayId can have maximum of %d characters.', self::PAY_ID_LENGTH_MAX));
        }
    }

    private static function checkWhitespaces(string $argument): void
    {
        $charlist = preg_quote(" \t\n\r\0\x0B\xC2\xA0", '#');
        preg_replace('#^[' . $charlist . ']+|[' . $charlist . ']+\z#u', '', $argument);

        if ($argument !== preg_replace('#^[' . $charlist . ']+|[' . $charlist . ']+\z#u', '', $argument)) {
            throw new InvalidArgumentException('Argument starts or ends with whitespace.');
        }
    }

    /**
     * @param int $ttlSec
     */
    public static function checkTtlSec($ttlSec): void
    {
        if ($ttlSec < self::TTL_SEC_MIN || $ttlSec > self::TTL_SEC_MAX) {
            throw new InvalidArgumentException(sprintf('TTL sec is out of range (%d - %d). Current value is %d.', self::TTL_SEC_MIN, self::TTL_SEC_MAX, $ttlSec));
        }
    }

    /**
     * @param int $ttlSec
     */
    public static function checkMallPayTtlSec($ttlSec): void
    {
        if ($ttlSec < self::MALL_PAY_TTL_SEC_MIN || $ttlSec > self::MALL_PAY_TTL_SEC_MAX) {
            throw new InvalidArgumentException(sprintf('TTL sec is out of range (%d - %d). Current value is %d.', self::MALL_PAY_TTL_SEC_MIN, self::MALL_PAY_TTL_SEC_MAX, $ttlSec));
        }
    }

    /**
     * @param string $value
     * @param int $maxLength
     */
    public static function checkWhitespacesAndLength($value, $maxLength): void
    {
        self::checkWhitespaces($value);

        if (mb_strlen($value) > $maxLength) {
            throw new InvalidArgumentException(sprintf('Field must have maximum of %d characters.', $maxLength));
        }
    }

    /**
     * @param int $value
     */
    public static function checkNumberPositiveOrZero($value): void
    {
        if ($value < 0) {
            throw new InvalidArgumentException('Value is negative.');
        }
    }

    /**
     * @param int $value
     */
    public static function checkNumberPositive($value): void
    {
        if ($value <= 0) {
            throw new InvalidArgumentException('Value is negative or zero.');
        }
    }

    /**
     * @param int $value
     * @param int $min
     * @param int $max
     */
    public static function checkNumberRange($value, $min, $max): void
    {
        if ($value < $min || $value > $max) {
            throw new InvalidArgumentException(sprintf('Value %d is not in range <%d, %d>.', $value, $min, $max));
        }
    }

    /**
     * @param int $value
     * @param int $min
     */
    public static function checkNumberGraterEqualThen($value, $min): void
    {
        if ($value < $min) {
            throw new InvalidArgumentException(sprintf('Value %d must be >= than %d.', $value, $min));
        }
    }

    /**
     * @param string $value
     */
    public static function checkEmail($value): void
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidArgumentException('E-mail is not valid.');
        }
    }

    /**
     * @param string $value
     */
    public static function checkUrl($value): void
    {
        if (filter_var($value, FILTER_VALIDATE_URL) === false) {
            throw new InvalidArgumentException('URL is not valid.');
        }
    }

    /**
     * @param string $phone
     */
    public static function checkPhone($phone): void
    {
        if (preg_match('~^(\\+|00)?\\d{1,3}\\.( *\\d+)+\\z~', $phone) !== 1) {
            throw new InvalidArgumentException(sprintf('Phone %s is not valid.', $phone));
        }
    }

}
