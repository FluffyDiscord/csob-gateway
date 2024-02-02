<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Crypto;

use RuntimeException;

class SigningFailedException extends RuntimeException
{

    /**
     * @var mixed[]
     */
    private $data;

    /**
     * @param mixed[] $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
        parent::__construct('Signing failed');
    }

    /**
     * @return mixed[]
     */
    public function getData(): array
    {
        return $this->data;
    }

}
