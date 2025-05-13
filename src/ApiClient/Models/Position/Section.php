<?php

namespace igormakarov\IKassa\ApiClient\Models\Position;

use InvalidArgumentException;

class Section
{
    private int $code;
    private string $name;

    public function __construct(int $code, string $name)
    {
        if($code < 0 || $code > 255) {
            throw new InvalidArgumentException(
                "Code '$code' is not a valid position section code: code must be between 0 and 255"
            );
        }
        $this->code = $code;
        $this->name = $name;
    }

    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'name' => $this->name,
        ];
    }
}