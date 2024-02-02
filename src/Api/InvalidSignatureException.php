<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Api;

use RuntimeException;

class InvalidSignatureException extends RuntimeException
{

    /**
     * @var mixed[]
     */
    private $responseData;

    /**
     * @param mixed[] $responseData
     */
    public function __construct(array $responseData)
    {
        $this->responseData = $responseData;
        parent::__construct('Invalid signature.');
    }

    /**
     * @return mixed[]
     */
    public function getResponseData(): array
    {
        return $this->responseData;
    }

}
