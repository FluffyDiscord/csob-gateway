<?php declare(strict_types=1);

namespace SlevomatCsobGateway\AdditionalData;

use SlevomatCsobGateway\Encodable;
use SlevomatCsobGateway\EncodeHelper;
use SlevomatCsobGateway\Price;
use SlevomatCsobGateway\Validator;
use function array_filter;

class OrderGiftcards implements Encodable
{

    /**
     * @var \SlevomatCsobGateway\Price|null
     */
    private $totalPrice;
    /**
     * @var int|null
     */
    private $quantity;
    public const QUANTITY_MIN = 0;
    public const QUANTITY_MAX = 99;

    public function __construct(?Price $totalPrice = null, ?int $quantity = null)
    {
        $this->totalPrice = $totalPrice;
        $this->quantity = $quantity;
        if ($this->quantity !== null) {
            Validator::checkNumberRange($this->quantity, self::QUANTITY_MIN, self::QUANTITY_MAX);
        }
    }

    /**
     * @return mixed[]
     */
    public function encode(): array
    {
        return array_filter([
            'totalAmount' => ($nullsafeVariable1 = $this->totalPrice) ? $nullsafeVariable1->getAmount() : null,
            'currency'    => (($nullsafeVariable2 = $this->totalPrice) ? $nullsafeVariable2->getCurrency() : null),
            'quantity'    => $this->quantity,
        ], EncodeHelper::filterValueCallback() === null ? function ($value, $key): bool {
            return !empty($value);
        } : EncodeHelper::filterValueCallback(), EncodeHelper::filterValueCallback() === null ? ARRAY_FILTER_USE_BOTH : 0);
    }

    /**
     * @return mixed[]
     */
    public static function encodeForSignature(): array
    {
        return [
            'totalAmount' => null,
            'currency'    => null,
            'quantity'    => null,
        ];
    }

}
