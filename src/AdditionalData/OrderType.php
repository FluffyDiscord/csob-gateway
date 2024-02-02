<?php declare(strict_types=1);

namespace SlevomatCsobGateway\AdditionalData;

class OrderType
{
    public const PURCHASE = 'purchase';
    public const BALANCE = 'balance';
    public const PREPAID = 'prepaid';
    public const CASH = 'cash';
    public const CHECK = 'check';
}
