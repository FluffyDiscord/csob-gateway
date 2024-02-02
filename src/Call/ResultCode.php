<?php declare(strict_types=1);

namespace SlevomatCsobGateway\Call;

class ResultCode
{
    public const C0_OK = 0;
    public const C100_MISSING_PARAMETER = 100;
    public const C110_INVALID_PARAMETER = 110;
    public const C120_MERCHANT_BLOCKED = 120;
    public const C130_SESSION_EXPIRED = 130;
    public const C140_PAYMENT_NOT_FOUND = 140;
    public const C150_PAYMENT_NOT_IN_VALID_STATE = 150;
    public const C160_PAYMENT_METHOD_DISABLED = 160;
    public const C170_PAYMENT_METHOD_UNAVAILABLE = 170;
    public const C180_OPERATION_NOT_ALLOWED = 180;
    public const C190_PAYMENT_METHOD_ERROR = 190;
    public const C400_CSOB_BUTTON_DISABLED = 400;
    public const C410_ERA_BUTTON_DISABLED = 410;
    public const C420_CSOB_BUTTON_UNAVAILABLE = 420;
    public const C430_ERA_BUTTON_UNAVAILABLE = 430;
    public const C500_EET_REJECTED = 500;
    public const C600_MALLPAY_PAYMENT_DECLINED = 600;
    public const C700_ONECLICK_TEMPLATE_NOT_FOUND = 700;
    public const C710_ONECLICK_TEMPLATE_EXPIRED = 710;
    public const C720_ONECLICK_TEMPLATE_CARD_EXPIRED = 720;
    public const C730_ONECLICK_CUSTOMER_REJECTED = 730;
    public const C740_ONECLICK_PAYMENT_REVERSED = 740;
    public const C750_CARDHOLDER_ACCOUNT_CLOSED = 750;
    public const C800_CUSTOMER_NOT_FOUND = 800;
    public const C810_CUSTOMER_FOUND_NOT_SAVED_CARD = 810;
    public const C820_CUSTOMER_FOUND_WITH_SAVED_CARD = 820;
    public const C900_INTERNAL_ERROR = 900;
    public const C999_GENERAL_ERROR = 999;
}
