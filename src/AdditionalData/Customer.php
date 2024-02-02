<?php declare(strict_types=1);

namespace SlevomatCsobGateway\AdditionalData;

use SlevomatCsobGateway\Encodable;
use SlevomatCsobGateway\EncodeHelper;
use SlevomatCsobGateway\Validator;
use function array_filter;

class Customer implements Encodable
{

    /**
     * @var string|null
     */
    private $name;
    /**
     * @var string|null
     */
    private $email;
    /**
     * @var string|null
     */
    private $homePhone;
    /**
     * @var string|null
     */
    private $workPhone;
    /**
     * @var string|null
     */
    private $mobilePhone;
    /**
     * @var \SlevomatCsobGateway\AdditionalData\CustomerAccount|null
     */
    private $customerAccount;
    /**
     * @var \SlevomatCsobGateway\AdditionalData\CustomerLogin|null
     */
    private $customerLogin;
    public const NAME_LENGTH_MAX = 45;
    public const EMAIL_LENGTH_MAX = 100;

    public function __construct(?string $name = null, ?string $email = null, ?string $homePhone = null, ?string $workPhone = null, ?string $mobilePhone = null, ?CustomerAccount $customerAccount = null, ?CustomerLogin $customerLogin = null)
    {
        $this->name = $name;
        $this->email = $email;
        $this->homePhone = $homePhone;
        $this->workPhone = $workPhone;
        $this->mobilePhone = $mobilePhone;
        $this->customerAccount = $customerAccount;
        $this->customerLogin = $customerLogin;
        if ($this->name !== null) {
            Validator::checkWhitespacesAndLength($this->name, self::NAME_LENGTH_MAX);
        }
        if ($this->email !== null) {
            Validator::checkWhitespacesAndLength($this->email, self::EMAIL_LENGTH_MAX);
            Validator::checkEmail($this->email);
        }
        if ($this->homePhone !== null) {
            Validator::checkPhone($this->homePhone);
        }
        if ($this->workPhone !== null) {
            Validator::checkPhone($this->workPhone);
        }
        if ($this->mobilePhone !== null) {
            Validator::checkPhone($this->mobilePhone);
        }
    }

    /**
     * @return mixed[]
     */
    public function encode(): array
    {
        return array_filter([
            'name'        => $this->name,
            'email'       => $this->email,
            'homePhone'   => $this->homePhone,
            'workPhone'   => $this->workPhone,
            'mobilePhone' => $this->mobilePhone,
            'account'     => ($nullsafeVariable1 = $this->customerAccount) ? $nullsafeVariable1->encode() : null,
            'login'       => ($nullsafeVariable2 = $this->customerLogin) ? $nullsafeVariable2->encode() : null,
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
            'name'        => null,
            'email'       => null,
            'homePhone'   => null,
            'workPhone'   => null,
            'mobilePhone' => null,
            'account'     => CustomerAccount::encodeForSignature(),
            'login'       => CustomerLogin::encodeForSignature(),
        ];
    }

}
