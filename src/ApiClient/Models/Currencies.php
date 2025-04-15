<?php

namespace igormakarov\IKassa\ApiClient\Models;

use Exception;

class Currencies
{
    public static string $BYN = 'BYN';
    public static string $USD = 'USD';
    public static string $EUR = 'EUR';
    public static string $RUB = 'RUB';

    public static function toArray(): array
    {
        return [self::$BYN, self::$USD, self::$EUR, self::$RUB];
    }

    /**
     * @throws Exception
     */
    public static function validate(string $type): void
    {
        if (!in_array($type, self::toArray(), true)) {
            throw new Exception('wrong currency');
        }
    }
}