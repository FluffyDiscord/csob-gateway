<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call;

use SlevomatCsobGateway\Encodable;
use SlevomatCsobGateway\EncodeHelper;
use function array_filter;

class ActionsAuthenticate implements Encodable
{

    /**
     * @var \SlevomatCsobGateway\Call\ActionsEndpoint|null
     */
    private $browserChallenge;
    /**
     * @var \SlevomatCsobGateway\Call\ActionsAuthenticateSdkChallenge|null
     */
    private $sdkChallenge;

    public function __construct(?ActionsEndpoint $browserChallenge = null, ?ActionsAuthenticateSdkChallenge $sdkChallenge = null)
    {
        $this->browserChallenge = $browserChallenge;
        $this->sdkChallenge = $sdkChallenge;
    }

    /**
     * @return mixed[]
     */
    public static function encodeForSignature(): array
    {
        return [
            'browserChallenge' => ActionsEndpoint::encodeForSignature(),
            'sdkChallenge'     => ActionsAuthenticateSdkChallenge::encodeForSignature(),
        ];
    }

    /**
     * @return mixed[]
     */
    public function encode(): array
    {
        return array_filter([
            'browserChallenge' => ($nullsafeVariable1 = $this->browserChallenge) ? $nullsafeVariable1->encode() : null,
            'sdkChallenge'     => ($nullsafeVariable2 = $this->sdkChallenge) ? $nullsafeVariable2->encode() : null,
        ], EncodeHelper::filterValueCallback() === null ? function ($value, $key): bool {
            return !empty($value);
        } : EncodeHelper::filterValueCallback(), EncodeHelper::filterValueCallback() === null ? ARRAY_FILTER_USE_BOTH : 0);
    }

    public function getBrowserChallenge(): ?ActionsEndpoint
    {
        return $this->browserChallenge;
    }

    public function getSdkChallenge(): ?ActionsAuthenticateSdkChallenge
    {
        return $this->sdkChallenge;
    }

}
