<?php

namespace igormakarov\IKassa\ApiClient\Models\Modifier;

class Discount implements IModifiable
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

    public function toModifier():Modifier
    {
        return new Modifier(-$this->sum, $this->name, $this->group);
    }
}