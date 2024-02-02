<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call;

class PaymentStatus
{
    public const S0_ERROR = 0;
    public const S1_CREATED = 1;
    public const S2_IN_PROGRESS = 2;
    public const S3_CANCELED = 3;
    public const S4_CONFIRMED = 4;
    public const S5_REVOKED = 5;
    public const S6_REJECTED = 6;
    public const S7_AWAITING_SETTLEMENT = 7;
    public const S8_CHARGED = 8;
    public const S9_PROCESSING_REFUND = 9;
    public const S10_PAYMENT_REFUNDED = 10;
}
