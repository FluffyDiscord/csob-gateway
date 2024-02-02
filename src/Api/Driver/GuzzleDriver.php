<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Api\Driver;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use SlevomatCsobGateway\Api\ApiClientDriver;
use SlevomatCsobGateway\Api\HttpMethod;
use SlevomatCsobGateway\Api\Response;
use SlevomatCsobGateway\Api\ResponseCode;
use Throwable;
use function array_map;
use function array_shift;
use function count;
use function json_decode;
use function json_encode;

class GuzzleDriver implements ApiClientDriver
{

    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param mixed[]|null $data
     * @param string[] $headers
     *
     * @param string $method
     * @param string $url
     * @throws GuzzleDriverException
     */
    public function request($method, $url, $data, $headers = []): Response
    {
        $postData = null;
        if ($method === HttpMethod::POST || $method === HttpMethod::PUT) {
            $postData = (string)json_encode($data);
        }
        $headers += ['Content-Type' => 'application/json'];
        $request = new Request($method, $url, $headers, $postData);

        try {
            $httpResponse = $this->client->send($request, [
                RequestOptions::HTTP_ERRORS     => false,
                RequestOptions::ALLOW_REDIRECTS => false,
            ]);

            $responseCode = $httpResponse->getStatusCode();

            /** @var string[]|string[][] $responseHeaders */
            $responseHeaders = array_map(static function ($item) {
                return count($item) > 1
                    ? $item
                    : array_shift($item);
            }, $httpResponse->getHeaders());

            return new Response($responseCode, json_decode((string)$httpResponse->getBody(), true), $responseHeaders);
        } catch (Throwable $e) {
            throw new GuzzleDriverException($e);
        }
    }

}
