<?php declare(strict_types=1);

namespace SlevomatCsobGateway;

use function array_map;

class Cart implements Encodable
{

    /**
     * @var string
     */
    private $currency;
    /** @var CartItem[] */
    private $items = [];

    public function __construct(string $currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return mixed[]
     */
    public function encode(): array
    {
        return array_map(static function (CartItem $item): array {
            return $item->encode();
        }, $this->items);
    }

    /**
     * @return mixed[]
     */
    public static function encodeForSignature(): array
    {
        return [
            CartItem::encodeForSignature(),
        ];
    }

    /**
     * @param string $name
     * @param int $quantity
     * @param int $amount
     * @param string|null $description
     */
    public function addItem($name, $quantity, $amount, $description = null): void
    {
        $this->items[] = new CartItem($name, $quantity, $amount, $description);
    }

    /**
     * @return CartItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getCurrentPrice(): Price
    {
        return new Price($this->countTotalAmount(), $this->currency);
    }

    private function countTotalAmount(): int
    {
        $totalAmount = 0;

        foreach ($this->items as $item) {
            $totalAmount += $item->getAmount();
        }

        return $totalAmount;
    }

}
