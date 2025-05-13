<?php

namespace igormakarov\IKassa\ApiClient\Models\Modifier;

class Modifiers
{
    /**
     * @var IModifiable[]
     */
    private array $modifiers;

    public function __construct(array $modifiers = [])
    {
        $this->modifiers = $modifiers;
    }

    public function toArray(): array
    {
        return array_map(function ($modifier) {
            return $modifier->toModifier()->toArray();
        }, $this->modifiers);
    }

}