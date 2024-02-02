<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Crypto;

use RuntimeException;
use Throwable;
use function sprintf;

class PublicKeyFileException extends RuntimeException
{

    /**
     * @var string
     */
    private $publicKeyFile;

    public function __construct(string $publicKeyFile, ?Throwable $previous = null)
    {
        $this->publicKeyFile = $publicKeyFile;
        parent::__construct(sprintf('Public key could not be loaded from file \'%s\'. Please make sure that the file contains valid public key.', $publicKeyFile), 0, $previous);
    }

    public function getPublicKeyFile(): string
    {
        return $this->publicKeyFile;
    }

}
