<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call\Button;

use SlevomatCsobGateway\Api\HttpMethod;
use SlevomatCsobGateway\Validator;

class PaymentButtonRedirect
{

    /**
     * @var string
     */
    private $method;
    /**
     * @var string
     */
    private $url;
    /**
     * @var mixed[]|null
     */
    private $params;

    /**
     * @param mixed[]|null $params
     */
    public function __construct(string $method, string $url, ?array $params = null)
    {
        $this->method = $method;
        $this->url = $url;
        $this->params = $params;
        Validator::checkReturnMethod($this->method);
    }

    /**
     * @return mixed[]
     */
    public static function encodeForSignature(): array
    {
        return [
            'method' => null,
            'url'    => null,
            'params' => null,
        ];
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @return mixed[]|null
     */
    public function getParams(): ?array
    {
        return $this->params;
    }

}
