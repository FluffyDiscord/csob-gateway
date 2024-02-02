<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Crypto;

use RuntimeException;
use function sprintf;

class VerificationFailedException extends RuntimeException
{

    /**
     * @var mixed[]
     */
    private $data;
    /**
     * @var string
     */
    private $errorMessage;

    /**
     * @param mixed[] $data
     */
    public function __construct(array $data, string $errorMessage)
    {
        $this->data = $data;
        $this->errorMessage = $errorMessage;
        parent::__construct(sprintf('Verification failed: %s', $errorMessage));
    }

    /**
     * @return mixed[]
     */
    public function getData(): array
    {
        return $this->data;
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

}
