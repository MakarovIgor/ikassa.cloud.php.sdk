<?php

namespace igormakarov\IKassa\ApiClient\Models\Position;

class TaxTypes
{
    public const TAX_0 = 0;
    public const TAX_10 = 10;
    public const TAX_20 = 20;
    public const TAX_25 = 25;

    public static function toArray(): array
    {
        return [
            TaxTypes::TAX_0,
            TaxTypes::TAX_10,
            TaxTypes::TAX_20,
            TaxTypes::TAX_25
        ];
    }
}