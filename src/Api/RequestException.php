<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Api;

use RuntimeException;

abstract class RequestException extends RuntimeException
{

    /**
     * @var \SlevomatCsobGateway\Api\Response
     */
    private $response;

    public function __construct(string $message, Response $response)
    {
        $this->response = $response;
        parent::__construct($message);
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

}
