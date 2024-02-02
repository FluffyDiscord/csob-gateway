<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Api\Driver;

use RuntimeException;
use SlevomatCsobGateway\Api\ApiClientDriverException;

class CurlDriverException extends RuntimeException implements ApiClientDriverException
{

    /**
     * @var mixed
     */
    private $info;

    /**
     * @param mixed $info
     */
    public function __construct(int $code, string $message, $info)
    {
        $this->info = $info;
        parent::__construct('Request error: ' . $message);

        $this->code = $code;
    }

    /**
     * @return mixed
     * @see curl_getinfo()
     */
    public function getInfo()
    {
        return $this->info;
    }

}
