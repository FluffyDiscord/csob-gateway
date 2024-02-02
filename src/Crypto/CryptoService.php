<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Crypto;

use function base64_decode;
use function base64_encode;
use function file_get_contents;
use const OPENSSL_ALGO_SHA256;
use const PHP_VERSION_ID;

class CryptoService
{

    /**
     * @var string
     */
    private $privateKeyFile;
    /**
     * @var string
     */
    private $bankPublicKeyFile;
    public const HASH_METHOD = OPENSSL_ALGO_SHA256;

    /**
     * @var string|null
     */
    private $privateKeyPassword;

    public function __construct(string $privateKeyFile, string $bankPublicKeyFile, string $privateKeyPassword = '')
    {
        $this->privateKeyFile = $privateKeyFile;
        $this->bankPublicKeyFile = $bankPublicKeyFile;
        $this->privateKeyPassword = $privateKeyPassword;
    }

    /**
     * @param mixed[] $data
     *
     * @param \SlevomatCsobGateway\Crypto\SignatureDataFormatter $signatureDataFormatter
     * @throws SigningFailedException
     * @throws PrivateKeyFileException
     */
    public function signData($data, $signatureDataFormatter): string
    {
        $message = $signatureDataFormatter->formatDataForSignature($data);

        /** @var string $privateKey */
        $privateKey = file_get_contents($this->privateKeyFile);
        $privateKeyId = openssl_pkey_get_private($privateKey, (string)$this->privateKeyPassword);
        if ($privateKeyId === false) {
            throw new PrivateKeyFileException($this->privateKeyFile);
        }

        $ok = openssl_sign($message, $signature, $privateKeyId, self::HASH_METHOD);
        if (!$ok) {
            throw new SigningFailedException($data);
        }

        $signature = base64_encode($signature);
        if (PHP_VERSION_ID < 80000) {
            // phpcs:ignore Generic.PHP.DeprecatedFunctions
            openssl_free_key($privateKeyId);
        }

        return $signature;
    }

    /**
     * @param mixed[] $data
     *
     * @param string $signature
     * @param \SlevomatCsobGateway\Crypto\SignatureDataFormatter $signatureDataFormatter
     * @throws PublicKeyFileException
     * @throws VerificationFailedException
     */
    public function verifyData($data, $signature, $signatureDataFormatter): bool
    {
        $message = $signatureDataFormatter->formatDataForSignature($data);

        $publicKey = (string)file_get_contents($this->bankPublicKeyFile);
        $publicKeyId = openssl_pkey_get_public($publicKey);
        if ($publicKeyId === false) {
            throw new PublicKeyFileException($this->bankPublicKeyFile);
        }

        $signature = base64_decode($signature, true);
        if ($signature === false) {
            throw new VerificationFailedException($data, 'Unable to decode signature.');
        }

        $verifyResult = openssl_verify($message, $signature, $publicKeyId, self::HASH_METHOD);
        if (PHP_VERSION_ID < 80000) {
            // phpcs:ignore Generic.PHP.DeprecatedFunctions
            openssl_free_key($publicKeyId);
        }
        if ($verifyResult === -1) {
            throw new VerificationFailedException($data, (string)openssl_error_string());
        }

        return $verifyResult === 1;
    }

}
