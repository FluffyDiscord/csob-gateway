<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Api;

interface ApiClientDriver
{

    /**
     * @param mixed[]|null $data
     * @param string[] $headers
     * @param string $method
     * @param string $url
     */
    public function request($method, $url, $data, $headers = []): Response;

}
