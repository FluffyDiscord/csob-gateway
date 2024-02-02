<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call;

use InvalidArgumentException;
use SlevomatCsobGateway\Api\HttpMethod;
use SlevomatCsobGateway\Encodable;
use SlevomatCsobGateway\EncodeHelper;
use function array_filter;

class ActionsEndpoint implements Encodable
{

    /**
     * @var string
     */
    private $url;
    /**
     * @var string|null
     */
    private $method;
    /**
     * @var mixed[]
     */
    private $vars;

    /**
     * @param mixed[] $vars
     */
    public function __construct(string $url, ?string $method = null, ?array $vars = null)
    {
        $this->url = $url;
        $this->method = $method;
        $this->vars = $vars;
        if ($this->method !== null && $this->method !== HttpMethod::GET && $this->method !== HttpMethod::POST) {
            throw new InvalidArgumentException('Only GET or POST are allowed.');
        }
    }

    /**
     * @return mixed[]
     */
    public static function encodeForSignature(): array
    {
        return [
            'url'    => null,
            'method' => null,
            'vars'   => [],
        ];
    }

    /**
     * @return mixed[]
     */
    public function encode(): array
    {
        return array_filter([
            'url'    => $this->url,
            'method' => ($nullsafeVariable1 = $this->method) ? $nullsafeVariable1 : null,
            'vars'   => $this->vars,
        ], EncodeHelper::filterValueCallback() === null ? function ($value, $key): bool {
            return !empty($value);
        } : EncodeHelper::filterValueCallback(), EncodeHelper::filterValueCallback() === null ? ARRAY_FILTER_USE_BOTH : 0);
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    /**
     * @return mixed[]|null
     */
    public function getVars(): ?array
    {
        return $this->vars;
    }

}
