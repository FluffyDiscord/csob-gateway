<?php declare(strict_types=1);

namespace SlevomatCsobGateway\MallPay;

use SlevomatCsobGateway\Encodable;
use SlevomatCsobGateway\EncodeHelper;
use SlevomatCsobGateway\Price;
use function array_filter;
use function array_map;

class OrderReference implements Encodable
{

    /**
     * @var \SlevomatCsobGateway\Price
     */
    private $totalPrice;
    /**
     * @var Vat[]
     */
    private $totalVat;
    /** @var OrderItemReference[] */
    private $items = [];

    /**
     * @param Vat[] $totalVat
     */
    public function __construct(Price $totalPrice, array $totalVat)
    {
        $this->totalPrice = $totalPrice;
        $this->totalVat = $totalVat;
    }

    /**
     * @param string $code
     * @param string|null $ean
     * @param string $name
     * @param string|null $type
     * @param int|null $quantity
     */
    public function addItem($code, $ean, $name, $type, $quantity): void
    {
        $this->items[] = new OrderItemReference($code, $ean, $name, $type, $quantity);
    }

    /**
     * @return mixed[]
     */
    public function encode(): array
    {
        return array_filter([
            'totalPrice' => $this->totalPrice->encode(),
            'totalVat'   => array_map(static function (Vat $vat): array {
                return $vat->encode();
            }, $this->totalVat),
            'items'      => array_map(static function (OrderItemReference $item): array {
                return $item->encode();
            }, $this->items),
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
            'totalPrice' => Price::encodeForSignature(),
            'totalVat'   => [
                Vat::encodeForSignature(),
            ],
            'items'      => [
                OrderItemReference::encodeForSignature(),
            ],
        ];
    }

    public function getTotalPrice(): Price
    {
        return $this->totalPrice;
    }

    /**
     * @return Vat[]
     */
    public function getTotalVat(): array
    {
        return $this->totalVat;
    }

    /**
     * @return OrderItemReference[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

}
