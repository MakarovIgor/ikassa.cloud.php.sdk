<?php

namespace igormakarov\IKassa\ApiClient\Models\Payment;

class Payments
{
    /**
     * @var Payment[]
     */
    private array $payments;

    public function __construct(array $payments)
    {
        $this->payments = $payments;
    }

    public function toArray(): array
    {
        return array_map(function ($payment) {
            return $payment->toArray();
        }, $this->payments);
    }
}