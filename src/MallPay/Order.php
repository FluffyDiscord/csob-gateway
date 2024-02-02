<?php declare(strict_types=1);

namespace SlevomatCsobGateway\MallPay;

use InvalidArgumentException;
use SlevomatCsobGateway\Encodable;
use SlevomatCsobGateway\EncodeHelper;
use SlevomatCsobGateway\Price;
use function array_filter;
use function array_map;

class Order implements Encodable
{

    /**
     * @var string
     */
    private $currency;
    /**
     * @var string|null
     */
    private $deliveryType;
    /** @var Address[] */
    private $addresses = [];

    /**
     * @var string|null
     */
    private $carrierId;

    /**
     * @var string|null
     */
    private $carrierCustom;

    /** @var OrderItem[] */
    private $items = [];

    public function __construct(string $currency, ?string $deliveryType, ?string $carrierId, ?string $carrierCustom)
    {
        $this->currency = $currency;
        $this->deliveryType = $deliveryType;
        if ($deliveryType === OrderDeliveryType::DELIVERY_CARRIER) {
            $this->carrierId = $carrierId;
            if ($carrierId === null) {
                if ($carrierCustom === null) {
                    throw new InvalidArgumentException('CarrierCustom is null.');
                }
                $this->carrierCustom = $carrierCustom;
            }
        }
    }

    /**
     * @param string[]|null $categories
     * @param string $code
     * @param string|null $ean
     * @param string $name
     * @param string|null $type
     * @param int|null $quantity
     * @param string|null $variant
     * @param string|null $description
     * @param string|null $producer
     * @param int|null $unitAmount
     * @param int $totalAmount
     * @param int|null $unitVatAmount
     * @param int $totalVatAmount
     * @param int $vatRate
     * @param string|null $productUrl
     */
    public function addItem($code, $ean, $name, $type, $quantity, $variant, $description, $producer, $categories, $unitAmount, $totalAmount, $unitVatAmount, $totalVatAmount, $vatRate, $productUrl): void
    {
        $this->items[] = new OrderItem($code, $ean, $name, $type, $quantity, $variant, $description, $producer, $categories, $unitAmount !== null ? new Price($unitAmount, $this->currency) : null, $unitVatAmount !== null ? new Vat($unitVatAmount, $this->currency, $vatRate) : null, new Price($totalAmount, $this->currency), new Vat($totalVatAmount, $this->currency, $vatRate), $productUrl);
    }

    /**
     * @param string|null $name
     * @param string $country
     * @param string $city
     * @param string $streetAddress
     * @param string|null $streetNumber
     * @param string $zip
     * @param string $addressType
     */
    public function addAddress($name, $country, $city, $streetAddress, $streetNumber, $zip, $addressType): void
    {
        $this->addresses[] = new Address($name, $country, $city, $streetAddress, $streetNumber, $zip, $addressType);
    }

    /**
     * @return mixed[]
     */
    public function encode(): array
    {
        return array_filter([
            'totalPrice'    => $this->getTotalPrice()->encode(),
            'totalVat'      => array_map(static function (Vat $vat): array {
                return $vat->encode();
            }, $this->getTotalVat()),
            'addresses'     => array_map(static function (Address $address): array {
                return $address->encode();
            }, $this->addresses),
            'deliveryType'  => ($nullsafeVariable1 = $this->deliveryType) ? $nullsafeVariable1 : null,
            'carrierId'     => ($nullsafeVariable2 = $this->carrierId) ? $nullsafeVariable2 : null,
            'carrierCustom' => $this->carrierCustom,
            'items'         => array_map(static function (OrderItem $item): array {
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
            'totalPrice'    => Price::encodeForSignature(),
            'totalVat'      => [
                Vat::encodeForSignature(),
            ],
            'addresses'     => [
                Address::encodeForSignature(),
            ],
            'deliveryType'  => null,
            'carrierId'     => null,
            'carrierCustom' => null,
            'items'         => [
                OrderItem::encodeForSignature(),
            ],
        ];
    }

    public function getTotalPrice(): Price
    {
        return new Price($this->countTotalPrice(), $this->currency);
    }

    private function countTotalPrice(): int
    {
        $totalAmount = 0;

        foreach ($this->items as $item) {
            $totalAmount += $item->getTotalPrice()->getAmount();
        }

        return $totalAmount;
    }

    /**
     * @return Vat[]
     */
    public function getTotalVat(): array
    {
        $vatRateAmounts = [];
        foreach ($this->items as $orderItem) {
            $vatRate = $orderItem->getTotalVat()->getVatRate();
            $vatRateAmounts[$vatRate] = ($vatRateAmounts[$vatRate] ?? 0) + $orderItem->getTotalVat()->getAmount();
        }

        $totalVatRates = [];
        foreach ($vatRateAmounts as $vatRate => $amount) {
            $totalVatRates[] = new Vat($amount, $this->currency, $vatRate);
        }

        return $totalVatRates;
    }

    /**
     * @return Address[]
     */
    public function getAddresses(): array
    {
        return $this->addresses;
    }

    public function getDeliveryType(): ?string
    {
        return $this->deliveryType;
    }

    public function getCarrierId(): ?string
    {
        return $this->carrierId;
    }

    public function getCarrierCustom(): ?string
    {
        return $this->carrierCustom;
    }

    /**
     * @return OrderItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

}
