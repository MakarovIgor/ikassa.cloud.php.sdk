<?php

namespace igormakarov\IKassa\ApiClient\Models\Modifier;

interface IModifiable
{
    public function toModifier(): Modifier;
}