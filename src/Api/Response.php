<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Api;

class Response
{

    /**
     * @var int
     */
    private $responseCode;
    /**
     * @var mixed[]|null
     */
    private $data;
    /**
     * @var string[]|string[][]
     */
    private $headers = [];
    /**
     * @var mixed[]
     */
    private $extensions = [];

    /**
     * @param mixed[]|null $data
     * @param string[]|string[][] $headers
     * @param mixed[] $extensions
     */
    public function __construct(int $responseCode, ?array $data = null, array $headers = [], array $extensions = [])
    {
        $this->responseCode = $responseCode;
        $this->data = $data;
        $this->headers = $headers;
        $this->extensions = $extensions;
    }

    public function getResponseCode(): int
    {
        return $this->responseCode;
    }

    /**
     * @return mixed[]|null
     */
    public function getData(): ?array
    {
        return $this->data;
    }

    /**
     * @return string[]|string[][]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return mixed[]
     */
    public function getExtensions(): array
    {
        return $this->extensions;
    }

}
