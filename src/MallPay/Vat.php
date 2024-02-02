<?php declare(strict_types=1);

namespace SlevomatCsobGateway\MallPay;

use SlevomatCsobGateway\Encodable;
use SlevomatCsobGateway\Validator;

class Vat implements Encodable
{

    /**
     * @var int
     */
    private $amount;
    /**
     * @var string
     */
    private $currency;
    /**
     * @var int
     */
    private $vatRate;

    /**
     * @param string $currency
     */
    public function __construct(int $amount, string $currency, int $vatRate)
    {
        $this->amount = $amount;
        $this->currency = $currency;
        $this->vatRate = $vatRate;
        Validator::checkNumberPositiveOrZero($amount);
    }

    /**
     * @return mixed[]
     */
    public function encode(): array
    {
        return [
            'amount'   => $this->amount,
            'currency' => $this->currency,
            'vatRate'  => $this->vatRate,
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
            'vatRate'  => null,
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

    public function getVatRate(): int
    {
        return $this->vatRate;
    }

}
