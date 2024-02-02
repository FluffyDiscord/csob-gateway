<?php declare(strict_types=1);

namespace SlevomatCsobGateway\MallPay;

use InvalidArgumentException;
use SlevomatCsobGateway\Encodable;
use SlevomatCsobGateway\EncodeHelper;
use SlevomatCsobGateway\Validator;
use function array_filter;

class Customer implements Encodable
{

    /**
     * @var string|null
     */
    private $firstName;
    /**
     * @var string|null
     */
    private $lastName;
    /**
     * @var string|null
     */
    private $fullName;
    /**
     * @var string|null
     */
    private $titleBefore;
    /**
     * @var string|null
     */
    private $titleAfter;
    /**
     * @var string
     */
    private $email;
    /**
     * @var string
     */
    private $phone;
    /**
     * @var string|null
     */
    private $tin;
    /**
     * @var string|null
     */
    private $vatin;
    public const NAME_LENGTH_MAX = 40;
    public const FULL_NAME_LENGTH_MAX = 100;
    public const TITLE_LENGTH_MAX = 20;
    public const EMAIL_LENGTH_MAX = 50;
    public const PHONE_LENGTH_MAX = 16;
    public const TIN_LENGTH_MAX = 10;
    public const VATIN_LENGTH_MAX = 12;

    public function __construct(?string $firstName, ?string $lastName, ?string $fullName, ?string $titleBefore, ?string $titleAfter, string $email, string $phone, ?string $tin = null, ?string $vatin = null)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->fullName = $fullName;
        $this->titleBefore = $titleBefore;
        $this->titleAfter = $titleAfter;
        $this->email = $email;
        $this->phone = $phone;
        $this->tin = $tin;
        $this->vatin = $vatin;
        if ($fullName === null && ($firstName === null || $lastName === null)) {
            throw new InvalidArgumentException('Either fullName or (firstName and lastName) must be set.');
        }
        Validator::checkEmail($email);
        Validator::checkWhitespacesAndLength($email, self::EMAIL_LENGTH_MAX);
        Validator::checkWhitespacesAndLength($phone, self::PHONE_LENGTH_MAX);
        if ($firstName !== null) {
            Validator::checkWhitespacesAndLength($firstName, self::NAME_LENGTH_MAX);
        }
        if ($lastName !== null) {
            Validator::checkWhitespacesAndLength($lastName, self::NAME_LENGTH_MAX);
        }
        if ($fullName !== null) {
            Validator::checkWhitespacesAndLength($fullName, self::FULL_NAME_LENGTH_MAX);
        }
        if ($titleBefore !== null) {
            Validator::checkWhitespacesAndLength($titleBefore, self::TITLE_LENGTH_MAX);
        }
        if ($titleAfter !== null) {
            Validator::checkWhitespacesAndLength($titleAfter, self::TITLE_LENGTH_MAX);
        }
        if ($tin !== null) {
            Validator::checkWhitespacesAndLength($tin, self::TIN_LENGTH_MAX);
        }
        if ($vatin !== null) {
            Validator::checkWhitespacesAndLength($vatin, self::VATIN_LENGTH_MAX);
        }
    }

    /**
     * @return mixed[]
     */
    public function encode(): array
    {
        return array_filter([
            'firstName'   => $this->firstName,
            'lastName'    => $this->lastName,
            'fullName'    => $this->fullName,
            'titleBefore' => $this->titleBefore,
            'titleAfter'  => $this->titleAfter,
            'email'       => $this->email,
            'phone'       => $this->phone,
            'tin'         => $this->tin,
            'vatin'       => $this->vatin,
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
            'firstName'   => null,
            'lastName'    => null,
            'fullName'    => null,
            'titleBefore' => null,
            'titleAfter'  => null,
            'email'       => null,
            'phone'       => null,
            'tin'         => null,
            'vatin'       => null,
        ];
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function getTitleBefore(): ?string
    {
        return $this->titleBefore;
    }

    public function getTitleAfter(): ?string
    {
        return $this->titleAfter;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getTin(): ?string
    {
        return $this->tin;
    }

    public function getVatin(): ?string
    {
        return $this->vatin;
    }

}
