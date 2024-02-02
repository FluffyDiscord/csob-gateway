<?php declare(strict_types=1);

namespace SlevomatCsobGateway;

class EncodeHelper
{

    public static function filterValueCallback(): callable
    {
        return static function ($value): bool {
            return $value !== null && $value !== [];
        };
    }

}
