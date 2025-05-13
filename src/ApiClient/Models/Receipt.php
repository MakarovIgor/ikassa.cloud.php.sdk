<?php

namespace igormakarov\IKassa\ApiClient\Models;

use igormakarov\IKassa\ApiClient\Models\Header\Header;
use igormakarov\IKassa\ApiClient\Models\Header\IHeader;
use igormakarov\IKassa\ApiClient\Models\Modifier\Modifiers;
use igormakarov\IKassa\ApiClient\Models\Payment\Payments;
use igormakarov\IKassa\ApiClient\Models\Position\Positions;

class Receipt
{
    private Header $header;
    private Positions $positions;
    private Modifiers $modifiers;
    private Payments $payments;

    public function __construct(Header $header, Positions $positions, Payments $payments, Modifiers $modifiers)
    {
        $this->header = $header;
        $this->positions = $positions;
        $this->payments = $payments;
        $this->modifiers = $modifiers;
    }

    public function toJson(): string
    {
        $result = [
            'header' => $this->header->toArray(),
            'items' => $this->positions->toArray(),
            'payments' => $this->payments->toArray(),
        ];
        if (!empty($this->modifiers->toArray())) {
            $result['modifiers'] = $this->modifiers->toArray();
        }

        var_dump(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}