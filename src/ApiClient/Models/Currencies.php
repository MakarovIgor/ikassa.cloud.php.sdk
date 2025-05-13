<?php

namespace igormakarov\IKassa\ApiClient\Models;

use Exception;
use http\Exception\InvalidArgumentException;

class Currencies
{
    public const BYN = 'BYN';
    public const USD = 'USD';
    public const EUR = 'EUR';
    public const RUB = 'RUB';

    public static function toArray(): array
    {
        return [
            Currencies::BYN,
            Currencies::USD,
            Currencies::EUR,
            Currencies::RUB
        ];
    }

    /**
     * @throws Exception
     */
    public static function validate(string $type): void
    {
        if (!in_array($type, self::toArray(), true)) {
            throw new InvalidArgumentException('wrong currency');
        }
    }
}