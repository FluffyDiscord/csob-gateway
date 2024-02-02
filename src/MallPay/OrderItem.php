<?php declare(strict_types=1);

namespace SlevomatCsobGateway\MallPay;

use SlevomatCsobGateway\Encodable;
use SlevomatCsobGateway\EncodeHelper;
use SlevomatCsobGateway\Price;
use SlevomatCsobGateway\Validator;
use function array_filter;

class OrderItem implements Encodable
{

    /**
     * @var string
     */
    private $code;
    /**
     * @var string|null
     */
    private $ean;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string|null
     */
    private $type;
    /**
     * @var int|null
     */
    private $quantity;
    /**
     * @var string|null
     */
    private $variant;
    /**
     * @var string|null
     */
    private $description;
    /**
     * @var string|null
     */
    private $producer;
    /**
     * @var string[]|null
     */
    private $categories;
    /**
     * @var \SlevomatCsobGateway\Price|null
     */
    private $unitPrice;
    /**
     * @var \SlevomatCsobGateway\MallPay\Vat|null
     */
    private $unitVat;
    /**
     * @var \SlevomatCsobGateway\Price
     */
    private $totalPrice;
    /**
     * @var \SlevomatCsobGateway\MallPay\Vat
     */
    private $totalVat;
    /**
     * @var string|null
     */
    private $productUrl;
    public const CODE_VARIANT_PRODUCER_LENGTH_MAX = 50;
    public const EAN_LENGTH_MAX = 15;
    public const NAME_LENGTH_MAX = 200;
    public const DESCRIPTION_LENGTH_MAX = 100;
    public const PRODUCT_URL_LENGTH_MAX = 250;

    /**
     * @param string[]|null $categories
     */
    public function __construct(string $code, ?string $ean, string $name, ?string $type, ?int $quantity, ?string $variant, ?string $description, ?string $producer, ?array $categories, ?Price $unitPrice, ?Vat $unitVat, Price $totalPrice, Vat $totalVat, ?string $productUrl = null)
    {
        $this->code = $code;
        $this->ean = $ean;
        $this->name = $name;
        $this->type = $type;
        $this->quantity = $quantity;
        $this->variant = $variant;
        $this->description = $description;
        $this->producer = $producer;
        $this->categories = $categories;
        $this->unitPrice = $unitPrice;
        $this->unitVat = $unitVat;
        $this->totalPrice = $totalPrice;
        $this->totalVat = $totalVat;
        $this->productUrl = $productUrl;
        Validator::checkWhitespacesAndLength($code, self::CODE_VARIANT_PRODUCER_LENGTH_MAX);
        Validator::checkWhitespacesAndLength($name, self::NAME_LENGTH_MAX);
        if ($ean !== null) {
            Validator::checkWhitespacesAndLength($ean, self::EAN_LENGTH_MAX);
        }
        if ($quantity !== null) {
            Validator::checkNumberPositive($quantity);
        }
        if ($variant !== null) {
            Validator::checkWhitespacesAndLength($variant, self::CODE_VARIANT_PRODUCER_LENGTH_MAX);
        }
        if ($description !== null) {
            Validator::checkWhitespacesAndLength($description, self::DESCRIPTION_LENGTH_MAX);
        }
        if ($producer !== null) {
            Validator::checkWhitespacesAndLength($producer, self::CODE_VARIANT_PRODUCER_LENGTH_MAX);
        }
        if ($productUrl !== null) {
            Validator::checkUrl($productUrl);
            Validator::checkWhitespacesAndLength($productUrl, self::PRODUCT_URL_LENGTH_MAX);
        }
    }

    /**
     * @return mixed[]
     */
    public function encode(): array
    {
        return array_filter([
            'code'        => $this->code,
            'ean'         => $this->ean,
            'name'        => $this->name,
            'type'        => ($nullsafeVariable1 = $this->type) ? $nullsafeVariable1 : null,
            'quantity'    => $this->quantity,
            'variant'     => $this->variant,
            'description' => $this->description,
            'producer'    => $this->producer,
            'categories'  => $this->categories,
            'unitPrice'   => ($nullsafeVariable2 = $this->unitPrice) ? $nullsafeVariable2->encode() : null,
            'unitVat'     => ($nullsafeVariable3 = $this->unitVat) ? $nullsafeVariable3->encode() : null,
            'totalPrice'  => $this->totalPrice->encode(),
            'totalVat'    => $this->totalVat->encode(),
            'productUrl'  => $this->productUrl,
        ], EncodeHelper::filterValueCallback() ?? function ($value, $key): bool {
            return !empty($value);
        }, EncodeHelper::filterValueCallback() === null ? ARRAY_FILTER_USE_BOTH : 0);
    }

    /**
     * @return mixed[]
     */
    public static function encodeForSignature(): array
    {
        return [
            'code'        => null,
            'ean'         => null,
            'name'        => null,
            'type'        => null,
            'quantity'    => null,
            'variant'     => null,
            'description' => null,
            'producer'    => null,
            'categories'  => [],
            'unitPrice'   => Price::encodeForSignature(),
            'unitVat'     => Vat::encodeForSignature(),
            'totalPrice'  => Price::encodeForSignature(),
            'totalVat'    => Vat::encodeForSignature(),
            'productUrl'  => null,
        ];
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getEan(): ?string
    {
        return $this->ean;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function getVariant(): ?string
    {
        return $this->variant;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getProducer(): ?string
    {
        return $this->producer;
    }

    /**
     * @return string[]|null
     */
    public function getCategories(): ?array
    {
        return $this->categories;
    }

    public function getUnitPrice(): ?Price
    {
        return $this->unitPrice;
    }

    public function getUnitVat(): ?Vat
    {
        return $this->unitVat;
    }

    public function getTotalPrice(): Price
    {
        return $this->totalPrice;
    }

    public function getTotalVat(): Vat
    {
        return $this->totalVat;
    }

    public function getProductUrl(): ?string
    {
        return $this->productUrl;
    }

}
