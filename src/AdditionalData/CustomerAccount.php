<?php declare(strict_types=1);

namespace SlevomatCsobGateway\AdditionalData;

use DateTimeImmutable;
use SlevomatCsobGateway\Encodable;
use SlevomatCsobGateway\EncodeHelper;
use SlevomatCsobGateway\Validator;
use function array_filter;
use const DATE_ATOM;

class CustomerAccount implements Encodable
{

    /**
     * @var \DateTimeImmutable|null
     */
    private $createdAt;
    /**
     * @var \DateTimeImmutable|null
     */
    private $changedAt;
    /**
     * @var \DateTimeImmutable|null
     */
    private $changedPwdAt;
    /**
     * @var int|null
     */
    private $orderHistory;
    /**
     * @var int|null
     */
    private $paymentsDay;
    /**
     * @var int|null
     */
    private $paymentsYear;
    /**
     * @var int|null
     */
    private $oneclickAdds;
    /**
     * @var bool|null
     */
    private $suspicious;
    public const QUANTITY_MIN = 0;
    public const QUANTITY_MAX = 9999;

    public function __construct(?DateTimeImmutable $createdAt = null, ?DateTimeImmutable $changedAt = null, ?DateTimeImmutable $changedPwdAt = null, ?int $orderHistory = null, ?int $paymentsDay = null, ?int $paymentsYear = null, ?int $oneclickAdds = null, ?bool $suspicious = null)
    {
        $this->createdAt = $createdAt;
        $this->changedAt = $changedAt;
        $this->changedPwdAt = $changedPwdAt;
        $this->orderHistory = $orderHistory;
        $this->paymentsDay = $paymentsDay;
        $this->paymentsYear = $paymentsYear;
        $this->oneclickAdds = $oneclickAdds;
        $this->suspicious = $suspicious;
        if ($this->orderHistory !== null) {
            Validator::checkNumberRange($this->orderHistory, self::QUANTITY_MIN, self::QUANTITY_MAX);
        }
        if ($this->paymentsDay !== null) {
            Validator::checkNumberRange($this->paymentsDay, self::QUANTITY_MIN, self::QUANTITY_MAX);
        }
        if ($this->paymentsYear !== null) {
            Validator::checkNumberRange($this->paymentsYear, self::QUANTITY_MIN, self::QUANTITY_MAX);
        }
        if ($this->oneclickAdds !== null) {
            Validator::checkNumberRange($this->oneclickAdds, self::QUANTITY_MIN, self::QUANTITY_MAX);
        }
    }

    /**
     * @return mixed[]
     */
    public function encode(): array
    {
        return array_filter([
            'createdAt'    => ($nullsafeVariable1 = $this->createdAt) ? $nullsafeVariable1->format(DATE_ATOM) : null,
            'changedAt'    => ($nullsafeVariable2 = $this->changedAt) ? $nullsafeVariable2->format(DATE_ATOM) : null,
            'changedPwdAt' => ($nullsafeVariable3 = $this->changedPwdAt) ? $nullsafeVariable3->format(DATE_ATOM) : null,
            'orderHistory' => $this->orderHistory,
            'paymentsDay'  => $this->paymentsDay,
            'paymentsYear' => $this->paymentsYear,
            'oneclickAdds' => $this->oneclickAdds,
            'suspicious'   => $this->suspicious,
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
            'createdAt'    => null,
            'changedAt'    => null,
            'changedPwdAt' => null,
            'orderHistory' => null,
            'paymentsDay'  => null,
            'paymentsYear' => null,
            'oneclickAdds' => null,
            'suspicious'   => null,
        ];
    }

}
