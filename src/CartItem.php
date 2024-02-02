<?php declare(strict_types=1);

namespace SlevomatCsobGateway;

use function array_filter;

class CartItem implements Encodable
{

    /**
     * @var string
     */
    private $name;
    /**
     * @var int
     */
    private $quantity;
    /**
     * @var int
     */
    private $amount;
    /**
     * @var string|null
     */
    private $description;

    public function __construct(string $name, int $quantity, int $amount, ?string $description = null)
    {
        $this->name = $name;
        $this->quantity = $quantity;
        $this->amount = $amount;
        $this->description = $description;
        Validator::checkCartItemName($name);
        if ($description !== null) {
            Validator::checkCartItemDescription($description);
        }
        Validator::checkCartItemQuantity($quantity);
    }

    /**
     * @return mixed[]
     */
    public function encode(): array
    {
        return array_filter([
            'name'        => $this->name,
            'quantity'    => $this->quantity,
            'amount'      => $this->amount,
            'description' => $this->description,
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
            'name'        => null,
            'quantity'    => null,
            'amount'      => null,
            'description' => null,
        ];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

}
