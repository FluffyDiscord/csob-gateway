<?php declare(strict_types=1);

namespace SlevomatCsobGateway\AdditionalData;

use InvalidArgumentException;
use SlevomatCsobGateway\Encodable;
use SlevomatCsobGateway\EncodeHelper;
use SlevomatCsobGateway\Validator;
use function array_filter;

class FingerprintBrowser implements Encodable
{

    /**
     * @var string
     */
    private $userAgent;
    /**
     * @var string
     */
    private $acceptHeader;
    /**
     * @var string
     */
    private $language;
    /**
     * @var bool
     */
    private $javascriptEnabled;
    /**
     * @var int|null
     */
    private $colorDepth;
    /**
     * @var int|null
     */
    private $screenHeight;
    /**
     * @var int|null
     */
    private $screenWidth;
    /**
     * @var int|null
     */
    private $timezone;
    /**
     * @var bool|null
     */
    private $javaEnabled;
    /**
     * @var string|null
     */
    private $challengeWindowSize;
    public const HEADER_LENGTH_MAX = 2048;
    public const LANGUAGE_LENGTH_MAX = 8;

    public function __construct(string $userAgent, string $acceptHeader, string $language, bool $javascriptEnabled, ?int $colorDepth, ?int $screenHeight, ?int $screenWidth, ?int $timezone, ?bool $javaEnabled, ?string $challengeWindowSize)
    {
        $this->userAgent = $userAgent;
        $this->acceptHeader = $acceptHeader;
        $this->language = $language;
        $this->javascriptEnabled = $javascriptEnabled;
        $this->colorDepth = $colorDepth;
        $this->screenHeight = $screenHeight;
        $this->screenWidth = $screenWidth;
        $this->timezone = $timezone;
        $this->javaEnabled = $javaEnabled;
        $this->challengeWindowSize = $challengeWindowSize;
        Validator::checkWhitespacesAndLength($this->userAgent, self::HEADER_LENGTH_MAX);
        Validator::checkWhitespacesAndLength($this->acceptHeader, self::HEADER_LENGTH_MAX);
        Validator::checkWhitespacesAndLength($this->language, self::LANGUAGE_LENGTH_MAX);
        if ($this->javascriptEnabled) {
            if ($this->colorDepth === null) {
                throw new InvalidArgumentException('If javascript is enabled `$colorDepth` is required');
            }
            if ($this->screenHeight === null) {
                throw new InvalidArgumentException('If javascript is enabled `$screenHeight` is required');
            }
            if ($this->screenWidth === null) {
                throw new InvalidArgumentException('If javascript is enabled `$screenWidth` is required');
            }
            if ($this->timezone === null) {
                throw new InvalidArgumentException('If javascript is enabled `$timezone` is required');
            }
            if ($this->javaEnabled === null) {
                throw new InvalidArgumentException('If javascript is enabled `$javaEnabled` is required');
            }
        }
        if ($this->screenHeight !== null) {
            Validator::checkNumberPositive($this->screenHeight);
        }
        if ($this->screenWidth !== null) {
            Validator::checkNumberPositive($this->screenWidth);
        }
    }

    /**
     * @return mixed[]
     */
    public function encode(): array
    {
        return array_filter([
            'userAgent'           => $this->userAgent,
            'acceptHeader'        => $this->acceptHeader,
            'language'            => $this->language,
            'javascriptEnabled'   => $this->javascriptEnabled,
            'colorDepth'          => ($nullsafeVariable1 = $this->colorDepth) ? $nullsafeVariable1 : null,
            'screenHeight'        => $this->screenHeight,
            'screenWidth'         => $this->screenWidth,
            'timezone'            => $this->timezone,
            'javaEnabled'         => $this->javaEnabled,
            'challengeWindowSize' => ($nullsafeVariable2 = $this->challengeWindowSize) ? $nullsafeVariable2 : null,
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
            'userAgent'           => null,
            'acceptHeader'        => null,
            'language'            => null,
            'javascriptEnabled'   => null,
            'colorDepth'          => null,
            'screenHeight'        => null,
            'screenWidth'         => null,
            'timezone'            => null,
            'javaEnabled'         => null,
            'challengeWindowSize' => null,
        ];
    }

}
