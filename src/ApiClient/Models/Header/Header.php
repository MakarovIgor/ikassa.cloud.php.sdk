<?php

namespace igormakarov\IKassa\ApiClient\Models\Header;

class Header implements IHeader
{
    private string $cashier;
    private string $currency;
    private object $attrs;

    public function __construct(string $cashier, string $currency, object $attrs = null)
    {
        $this->cashier = $cashier;
        $this->currency = $currency;
        $this->attrs = $attrs ?: new \stdClass();
    }

    public function toArray(): array
    {
        return [
            'cashier' => $this->cashier,
            'currency' => $this->currency,
            '@attrs' => $this->attrs
        ];
    }
}