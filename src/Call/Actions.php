<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call;

use SlevomatCsobGateway\Encodable;
use SlevomatCsobGateway\EncodeHelper;
use function array_filter;

class Actions implements Encodable
{

    /**
     * @var \SlevomatCsobGateway\Call\ActionsFingerprint|null
     */
    private $fingerprint;
    /**
     * @var \SlevomatCsobGateway\Call\ActionsAuthenticate|null
     */
    private $authenticate;

    public function __construct(?ActionsFingerprint $fingerprint = null, ?ActionsAuthenticate $authenticate = null)
    {
        $this->fingerprint = $fingerprint;
        $this->authenticate = $authenticate;
    }

    /**
     * @return mixed[]
     */
    public static function encodeForSignature(): array
    {
        return [
            'fingerprint'  => ActionsFingerprint::encodeForSignature(),
            'authenticate' => ActionsAuthenticate::encodeForSignature(),
        ];
    }

    /**
     * @return mixed[]
     */
    public function encode(): array
    {
        return array_filter([
            'fingerprint'  => ($nullsafeVariable1 = $this->fingerprint) ? $nullsafeVariable1->encode() : null,
            'authenticate' => ($nullsafeVariable2 = $this->authenticate) ? $nullsafeVariable2->encode() : null,
        ], EncodeHelper::filterValueCallback() === null ? function ($value, $key): bool {
            return !empty($value);
        } : EncodeHelper::filterValueCallback(), EncodeHelper::filterValueCallback() === null ? ARRAY_FILTER_USE_BOTH : 0);
    }

    public function getFingerprint(): ?ActionsFingerprint
    {
        return $this->fingerprint;
    }

    public function getAuthenticate(): ?ActionsAuthenticate
    {
        return $this->authenticate;
    }

}
