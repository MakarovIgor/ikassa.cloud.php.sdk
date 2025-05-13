<?php

namespace igormakarov\IKassa\ApiClient\Models\Payment;

class Payment
{
    private int $type;
    private int $sum;
    public function __construct(int $type, int $sum)
    {
        $this->type = $type;
        $this->sum = $sum;
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'sum' => $this->sum,
        ];
    }
}