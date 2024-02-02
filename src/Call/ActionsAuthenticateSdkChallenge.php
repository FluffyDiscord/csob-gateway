<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call;

use SlevomatCsobGateway\Encodable;
use SlevomatCsobGateway\EncodeHelper;
use function array_filter;

class ActionsAuthenticateSdkChallenge implements Encodable
{

    /**
     * @var string
     */
    private $threeDSServerTransID;
    /**
     * @var string
     */
    private $acsReferenceNumber;
    /**
     * @var string
     */
    private $acsTransID;
    /**
     * @var string
     */
    private $acsSignedContent;

    public function __construct(string $threeDSServerTransID, string $acsReferenceNumber, string $acsTransID, string $acsSignedContent)
    {
        $this->threeDSServerTransID = $threeDSServerTransID;
        $this->acsReferenceNumber = $acsReferenceNumber;
        $this->acsTransID = $acsTransID;
        $this->acsSignedContent = $acsSignedContent;
    }

    /**
     * @return mixed[]
     */
    public static function encodeForSignature(): array
    {
        return [
            'threeDSServerTransID' => null,
            'acsReferenceNumber'   => null,
            'acsTransID'           => null,
            'acsSignedContent'     => null,
        ];
    }

    /**
     * @return mixed[]
     */
    public function encode(): array
    {
        return array_filter([
            'threeDSServerTransID' => $this->threeDSServerTransID,
            'acsReferenceNumber'   => $this->acsReferenceNumber,
            'acsTransID'           => $this->acsTransID,
            'acsSignedContent'     => $this->acsSignedContent,
        ], EncodeHelper::filterValueCallback() === null ? function ($value, $key): bool {
            return !empty($value);
        } : EncodeHelper::filterValueCallback(), EncodeHelper::filterValueCallback() === null ? ARRAY_FILTER_USE_BOTH : 0);
    }

    public function getThreeDSServerTransID(): string
    {
        return $this->threeDSServerTransID;
    }

    public function getAcsReferenceNumber(): string
    {
        return $this->acsReferenceNumber;
    }

    public function getAcsTransID(): string
    {
        return $this->acsTransID;
    }

    public function getAcsSignedContent(): string
    {
        return $this->acsSignedContent;
    }

}
