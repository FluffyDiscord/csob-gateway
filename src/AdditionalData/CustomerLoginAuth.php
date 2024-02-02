<?php declare(strict_types=1);

namespace SlevomatCsobGateway\AdditionalData;

class CustomerLoginAuth
{
    public const GUEST = 'guest';
    public const ACCOUNT = 'account';
    public const FEDERATED = 'federated';
    public const ISSUER = 'issuer';
    public const THIRD_PARTY = 'thirdparty';
    public const FIDO = 'fido';
    public const FIDO_SIGNED = 'fido_signed';
    public const API = 'api';
}
