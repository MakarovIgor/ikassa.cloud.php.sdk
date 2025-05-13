<?php

namespace igormakarov\IKassa\ApiClient\Models\Position;

class Positions
{

    /**
     * @var Position[]
     */
    private array $positions;


    public function __construct(array $positions)
    {
        $this->positions = $positions;
    }

    public function toArray(): array
    {
        $result = [];
        foreach ($this->positions as $position) {
            $result[] = $position->toArray();
        }
        return $result;
    }
}