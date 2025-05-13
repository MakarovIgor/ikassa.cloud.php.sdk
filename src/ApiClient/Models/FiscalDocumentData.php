<?php

namespace igormakarov\IKassa\ApiClient\Models;

use igormakarov\IKassa\ApiClient\Models\Header\IHeader;

class FiscalDocumentData
{
    private IHeader $header;
    private int $sum;
    public function __construct(IHeader $header, int $sum)
    {
        $this->header = $header;
        $this->sum = $sum;
    }

    public function toArray(): array
    {
        return [
            'header' => $this->header->toArray(),
            'sum' => $this->sum
        ];
    }
    public function toJson(): string
    {

        return json_encode($this->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}