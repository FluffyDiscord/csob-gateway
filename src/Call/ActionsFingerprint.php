<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call;

use SlevomatCsobGateway\Encodable;
use SlevomatCsobGateway\EncodeHelper;
use function array_filter;

class ActionsFingerprint implements Encodable
{

    /**
     * @var \SlevomatCsobGateway\Call\ActionsEndpoint|null
     */
    private $browserInit;
    /**
     * @var \SlevomatCsobGateway\Call\ActionsFingerprintSdkInit|null
     */
    private $sdkInit;

    public function __construct(?ActionsEndpoint $browserInit = null, ?ActionsFingerprintSdkInit $sdkInit = null)
    {
        $this->browserInit = $browserInit;
        $this->sdkInit = $sdkInit;
    }

    /**
     * @return mixed[]
     */
    public static function encodeForSignature(): array
    {
        return [
            'browserInit' => ActionsEndpoint::encodeForSignature(),
            'sdkInit'     => ActionsFingerprintSdkInit::encodeForSignature(),
        ];
    }

    /**
     * @return mixed[]
     */
    public function encode(): array
    {
        return array_filter([
            'browserInit' => ($nullsafeVariable1 = $this->browserInit) ? $nullsafeVariable1->encode() : null,
            'sdkInit'     => ($nullsafeVariable2 = $this->sdkInit) ? $nullsafeVariable2->encode() : null,
        ], EncodeHelper::filterValueCallback() === null ? function ($value, $key): bool {
            return !empty($value);
        } : EncodeHelper::filterValueCallback(), EncodeHelper::filterValueCallback() === null ? ARRAY_FILTER_USE_BOTH : 0);
    }

    public function getBrowserInit(): ?ActionsEndpoint
    {
        return $this->browserInit;
    }

    public function getSdkInit(): ?ActionsFingerprintSdkInit
    {
        return $this->sdkInit;
    }

}
