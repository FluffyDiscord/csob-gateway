<?php declare(strict_types=1);

namespace SlevomatCsobGateway\AdditionalData;

use DateTimeImmutable;
use InvalidArgumentException;
use SlevomatCsobGateway\Encodable;
use SlevomatCsobGateway\EncodeHelper;
use SlevomatCsobGateway\Validator;
use function array_filter;
use const DATE_ATOM;

class Order implements Encodable
{

    /**
     * @var string|null
     */
    private $type;
    /**
     * @var string|null
     */
    private $availability;
    /**
     * @var \DateTimeImmutable|null
     */
    private $availabilityDate;
    /**
     * @var string|null
     */
    private $delivery;
    /**
     * @var string|null
     */
    private $deliveryMode;
    /**
     * @var string|null
     */
    private $deliveryEmail;
    /**
     * @var bool|null
     */
    private $nameMatch;
    /**
     * @var bool|null
     */
    private $addressMatch;
    /**
     * @var \SlevomatCsobGateway\AdditionalData\OrderAddress|null
     */
    private $billing;
    /**
     * @var \SlevomatCsobGateway\AdditionalData\OrderAddress|null
     */
    private $shipping;
    /**
     * @var \DateTimeImmutable|null
     */
    private $shippingAddedAt;
    /**
     * @var bool|null
     */
    private $reorder;
    /**
     * @var \SlevomatCsobGateway\AdditionalData\OrderGiftcards|null
     */
    private $giftcards;
    public const EMAIL_LENGTH_MAX = 100;

    public function __construct(?string $type = null, ?string $availability = null, ?DateTimeImmutable $availabilityDate = null, ?string $delivery = null, ?string $deliveryMode = null, ?string $deliveryEmail = null, ?bool $nameMatch = null, ?bool $addressMatch = null, ?OrderAddress $billing = null, ?OrderAddress $shipping = null, ?DateTimeImmutable $shippingAddedAt = null, ?bool $reorder = null, ?OrderGiftcards $giftcards = null)
    {
        $this->type = $type;
        $this->availability = $availability;
        $this->availabilityDate = $availabilityDate;
        $this->delivery = $delivery;
        $this->deliveryMode = $deliveryMode;
        $this->deliveryEmail = $deliveryEmail;
        $this->nameMatch = $nameMatch;
        $this->addressMatch = $addressMatch;
        $this->billing = $billing;
        $this->shipping = $shipping;
        $this->shippingAddedAt = $shippingAddedAt;
        $this->reorder = $reorder;
        $this->giftcards = $giftcards;
        if ($this->availability === OrderAvailability::DATE xor $this->availabilityDate !== null) {
            throw new InvalidArgumentException('If $availability is set to DATE, $availabilityDate must be provided.');
        }
        if ($this->deliveryEmail !== null) {
            Validator::checkWhitespacesAndLength($this->deliveryEmail, self::EMAIL_LENGTH_MAX);
            Validator::checkEmail($this->deliveryEmail);
        }
    }

    /**
     * @return mixed[]
     */
    public function encode(): array
    {
        return array_filter([
            'type'            => ($nullsafeVariable1 = $this->type) ? $nullsafeVariable1 : null,
            'availability'    => $this->availability === OrderAvailability::DATE ? ($nullsafeVariable2 = $this->availabilityDate) ? $nullsafeVariable2->format(DATE_ATOM) : null : (($nullsafeVariable3 = $this->availability) ? $nullsafeVariable3 : null),
            'delivery'        => ($nullsafeVariable4 = $this->delivery) ? $nullsafeVariable4 : null,
            'deliveryMode'    => $this->deliveryMode,
            'deliveryEmail'   => $this->deliveryEmail,
            'nameMatch'       => $this->nameMatch,
            'addressMatch'    => $this->addressMatch,
            'billing'         => ($nullsafeVariable6 = $this->billing) ? $nullsafeVariable6->encode() : null,
            'shipping'        => ($nullsafeVariable7 = $this->shipping) ? $nullsafeVariable7->encode() : null,
            'shippingAddedAt' => ($nullsafeVariable8 = $this->shippingAddedAt) ? $nullsafeVariable8->format(DATE_ATOM) : null,
            'reorder'         => $this->reorder,
            'giftcards'       => ($nullsafeVariable9 = $this->giftcards) ? $nullsafeVariable9->encode() : null,
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
            'type'            => null,
            'availability'    => null,
            'delivery'        => null,
            'deliveryMode'    => null,
            'deliveryEmail'   => null,
            'nameMatch'       => null,
            'addressMatch'    => null,
            'billing'         => OrderAddress::encodeForSignature(),
            'shipping'        => OrderAddress::encodeForSignature(),
            'shippingAddedAt' => null,
            'reorder'         => null,
            'giftcards'       => OrderGiftcards::encodeForSignature(),
        ];
    }

}
