<?php declare(strict_types=1);

namespace SlevomatCsobGateway\AdditionalData;

use SlevomatCsobGateway\Encodable;
use SlevomatCsobGateway\EncodeHelper;
use SlevomatCsobGateway\Validator;
use function array_filter;

class FingerprintSdk implements Encodable
{

    /**
     * @var string
     */
    private $appID;
    /**
     * @var string
     */
    private $encData;
    /**
     * @var string
     */
    private $ephemPubKey;
    /**
     * @var int
     */
    private $maxTimeout;
    /**
     * @var string
     */
    private $referenceNumber;
    /**
     * @var string
     */
    private $transID;
    public const MAX_TIMEOUT_MIN = 5;
    public const REFERENCE_NUMBER_LENGTH_MAX = 32;
    public const TRANS_ID_LENGTH_MAX = 36;

    public function __construct(string $appID, string $encData, string $ephemPubKey, int $maxTimeout, string $referenceNumber, string $transID)
    {
        $this->appID = $appID;
        $this->encData = $encData;
        $this->ephemPubKey = $ephemPubKey;
        $this->maxTimeout = $maxTimeout;
        $this->referenceNumber = $referenceNumber;
        $this->transID = $transID;
        Validator::checkNumberGraterEqualThen($this->maxTimeout, self::MAX_TIMEOUT_MIN);
        Validator::checkWhitespacesAndLength($this->referenceNumber, self::REFERENCE_NUMBER_LENGTH_MAX);
        Validator::checkWhitespacesAndLength($this->transID, self::TRANS_ID_LENGTH_MAX);
    }

    /**
     * @return mixed[]
     */
    public function encode(): array
    {
        return array_filter([
            'appID'           => $this->appID,
            'encData'         => $this->encData,
            'ephemPubKey'     => $this->ephemPubKey,
            'maxTimeout'      => $this->maxTimeout,
            'referenceNumber' => $this->referenceNumber,
            'transID'         => $this->transID,
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
            'appID'           => null,
            'encData'         => null,
            'ephemPubKey'     => null,
            'maxTimeout'      => null,
            'referenceNumber' => null,
            'transID'         => null,
        ];
    }

}
