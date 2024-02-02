<?php declare(strict_types=1);

namespace SlevomatCsobGateway;

class Price implements Encodable
{

    /**
     * @var int
     */
    private $amount;
    /**
     * @var string
     */
    private $currency;

    public function __construct(int $amount, string $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    /**
     * @return mixed[]
     */
    public function encode(): array
    {
        return [
            'amount'   => $this->amount,
            'currency' => $this->currency,
        ];
    }

    /**
     * @return mixed[]
     */
    public static function encodeForSignature(): array
    {
        return [
            'amount'   => null,
            'currency' => null,
        ];
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

}
