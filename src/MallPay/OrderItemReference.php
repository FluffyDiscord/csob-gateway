<?php declare(strict_types=1);

namespace SlevomatCsobGateway\MallPay;

use SlevomatCsobGateway\Encodable;
use SlevomatCsobGateway\EncodeHelper;
use SlevomatCsobGateway\Validator;
use function array_filter;

class OrderItemReference implements Encodable
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

    public function __construct(string $code, ?string $ean, string $name, ?string $type = null, ?int $quantity = null)
    {
        $this->code = $code;
        $this->ean = $ean;
        $this->name = $name;
        $this->type = $type;
        $this->quantity = $quantity;
        Validator::checkWhitespacesAndLength($code, OrderItem::CODE_VARIANT_PRODUCER_LENGTH_MAX);
        Validator::checkWhitespacesAndLength($name, OrderItem::NAME_LENGTH_MAX);
        if ($ean !== null) {
            Validator::checkWhitespacesAndLength($ean, OrderItem::EAN_LENGTH_MAX);
        }
        if ($quantity !== null) {
            Validator::checkNumberPositive($quantity);
        }
    }

    /**
     * @return mixed[]
     */
    public function encode(): array
    {
        return array_filter([
            'code'     => $this->code,
            'ean'      => $this->ean,
            'name'     => $this->name,
            'type'     => ($nullsafeVariable1 = $this->type) ? $nullsafeVariable1 : null,
            'quantity' => $this->quantity,
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
            'code'     => null,
            'ean'      => null,
            'name'     => null,
            'type'     => null,
            'quantity' => null,
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

}
