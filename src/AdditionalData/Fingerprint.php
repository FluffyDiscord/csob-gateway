<?php declare(strict_types=1);

namespace SlevomatCsobGateway\AdditionalData;

use SlevomatCsobGateway\Encodable;
use SlevomatCsobGateway\EncodeHelper;
use function array_filter;

class Fingerprint implements Encodable
{

    /**
     * @var \SlevomatCsobGateway\AdditionalData\FingerprintBrowser|null
     */
    private $browser;
    /**
     * @var \SlevomatCsobGateway\AdditionalData\FingerprintSdk|null
     */
    private $sdk;

    public function __construct(?FingerprintBrowser $browser = null, ?FingerprintSdk $sdk = null)
    {
        $this->browser = $browser;
        $this->sdk = $sdk;
    }

    /**
     * @return mixed[]
     */
    public function encode(): array
    {
        return array_filter([
            'browser' => ($nullsafeVariable1 = $this->browser) ? $nullsafeVariable1->encode() : null,
            'sdk'     => ($nullsafeVariable2 = $this->sdk) ? $nullsafeVariable2->encode() : null,
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
            'browser' => FingerprintBrowser::encodeForSignature(),
            'sdk'     => FingerprintSdk::encodeForSignature(),
        ];
    }

}
