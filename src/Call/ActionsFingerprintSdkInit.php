<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call;

use SlevomatCsobGateway\Encodable;
use SlevomatCsobGateway\EncodeHelper;
use function array_filter;

class ActionsFingerprintSdkInit implements Encodable
{

    /**
     * @var string
     */
    private $directoryServerID;
    /**
     * @var string
     */
    private $schemeId;
    /**
     * @var string
     */
    private $messageVersion;

    public function __construct(string $directoryServerID, string $schemeId, string $messageVersion)
    {
        $this->directoryServerID = $directoryServerID;
        $this->schemeId = $schemeId;
        $this->messageVersion = $messageVersion;
    }

    /**
     * @return mixed[]
     */
    public static function encodeForSignature(): array
    {
        return [
            'directoryServerID' => null,
            'schemeId'          => null,
            'messageVersion'    => null,
        ];
    }

    /**
     * @return mixed[]
     */
    public function encode(): array
    {
        return array_filter([
            'directoryServerID' => $this->directoryServerID,
            'schemeId'          => $this->schemeId,
            'messageVersion'    => $this->messageVersion,
        ], EncodeHelper::filterValueCallback() === null ? function ($value, $key): bool {
            return !empty($value);
        } : EncodeHelper::filterValueCallback(), EncodeHelper::filterValueCallback() === null ? ARRAY_FILTER_USE_BOTH : 0);
    }

    public function getDirectoryServerID(): string
    {
        return $this->directoryServerID;
    }

    public function getSchemeId(): string
    {
        return $this->schemeId;
    }

    public function getMessageVersion(): string
    {
        return $this->messageVersion;
    }

}
