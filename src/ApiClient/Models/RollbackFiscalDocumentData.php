<?php

namespace igormakarov\IKassa\ApiClient\Models;

use igormakarov\IKassa\ApiClient\Models\Header\IHeader;
use igormakarov\IKassa\ApiClient\Models\Header\RollbackHeader;

class RollbackFiscalDocumentData
{
    private IHeader $header;
    private int $number;
    public function __construct(RollbackHeader $header, int $number)
    {
        $this->header = $header;
        $this->number = $number;
    }

    public function toArray(): array
    {
        return [
            'header' => $this->header->toArray(),
            'number' => $this->number
        ];
    }
    public function toJson(): string
    {

        return json_encode($this->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}