<?php declare(strict_types=1);

namespace SlevomatCsobGateway\AdditionalData;

use DateTimeImmutable;
use SlevomatCsobGateway\Encodable;
use SlevomatCsobGateway\EncodeHelper;
use function array_filter;
use function in_array;
use const DATE_ATOM;

class CustomerLogin implements Encodable
{

    /**
     * @var string|null
     */
    private $auth;
    /**
     * @var \DateTimeImmutable|null
     */
    private $authAt;
    /**
     * @var string|null
     */
    private $authData;

    public function __construct(?string $auth = null, ?DateTimeImmutable $authAt = null, ?string $authData = null)
    {
        $this->auth = $auth;
        $this->authAt = $authAt;
        $allowedAuthDataFor = [
            CustomerLoginAuth::FEDERATED,
            CustomerLoginAuth::FIDO,
            CustomerLoginAuth::FIDO_SIGNED,
            CustomerLoginAuth::API,
        ];
        if ($authData !== null && in_array($this->auth, $allowedAuthDataFor, true)) {
            $this->authData = $authData;
        }
    }

    /**
     * @return mixed[]
     */
    public function encode(): array
    {
        return array_filter([
            'auth'     => ($nullsafeVariable1 = $this->auth) ? $nullsafeVariable1 : null,
            'authAt'   => ($nullsafeVariable2 = $this->authAt) ? $nullsafeVariable2->format(DATE_ATOM) : null,
            'authData' => $this->authData,
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
            'auth'     => null,
            'authAt'   => null,
            'authData' => null,
        ];
    }

}
