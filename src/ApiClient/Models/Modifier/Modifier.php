<?php

namespace igormakarov\IKassa\ApiClient\Models\Modifier;

class Modifier
{
    private int $sum;
    private string $name;
    private string $group;
    public function __construct(int $sum, string $name = "", string $group = "")
    {
        $this->sum = $sum;
        $this->name = $name;
        $this->group = $group;
    }

    public function toArray(): array
    {
        $result = [
            'modifier' => $this->sum
        ];
        if (!empty($this->name)) {
            $result['name'] = $this->name;
        }
        if (!empty($this->group)) {
            $result['group'] = $this->group;
        }
        return $result;
    }
}