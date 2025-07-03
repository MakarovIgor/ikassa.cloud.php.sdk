<?php

namespace igormakarov\IKassa\ApiClient\Models;

use igormakarov\IKassa\ApiClient\Models\Header\Header;
use igormakarov\IKassa\ApiClient\Models\Modifier\Modifiers;
use igormakarov\IKassa\ApiClient\Models\Payment\Payments;
use igormakarov\IKassa\ApiClient\Models\Position\Positions;

class RefundReceipt
{
    private Header $header;
    private Positions $positions;
    private Payments $payments;

    public function __construct(Header $header, Positions $positions, Payments $payments)
    {
        $this->header = $header;
        $this->positions = $positions;
        $this->payments = $payments;
    }

    public function toJson(): string
    {
        $result = [
            'header' => $this->header->toArray(),
            'items' => $this->positions->toArray(),
            'payments' => $this->payments->toArray(),
        ];

        return json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}