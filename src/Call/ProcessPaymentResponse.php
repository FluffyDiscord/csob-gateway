<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call;

class ProcessPaymentResponse implements Response
{

    /**
     * @var string
     */
    private $gatewayLocationUrl;

    public function __construct(string $gatewayLocationUrl)
    {
        $this->gatewayLocationUrl = $gatewayLocationUrl;
    }

    /**
     * @param mixed[] $data
     * @return $this
     */
    public static function createFromResponseData($data): \SlevomatCsobGateway\Call\Response
    {
        return new self($data['gatewayLocationUrl']);
    }

    /**
     * @return mixed[]
     */
    public static function encodeForSignature(): array
    {
        return [
            'payId'         => null,
            'dttm'          => null,
            'resultCode'    => null,
            'resultMessage' => null,
            'paymentStatus' => null,
            'authCode'      => null,
            'merchantData'  => null,
            'statusDetail'  => null,
        ];
    }

    /**
     * @return mixed[]
     */
    public function encode(): array
    {
        return [
            'gatewayLocationUrl' => $this->gatewayLocationUrl,
        ];
    }

    public function getGatewayLocationUrl(): string
    {
        return $this->gatewayLocationUrl;
    }

}
