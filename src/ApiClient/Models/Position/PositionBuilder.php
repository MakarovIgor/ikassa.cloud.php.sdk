<?php

namespace igormakarov\IKassa\ApiClient\Models\Position;

use igormakarov\IKassa\ApiClient\Models\Modifier\Discount;
use igormakarov\IKassa\ApiClient\Models\Modifier\Markup;

class PositionBuilder
{
    private Position $position;

    public function __construct(string $name, int $cost, int $quantity)
    {
        $this->position = new Position($name, $cost, $quantity);
    }

    public function setDiscount(Discount $discount): PositionBuilder
    {
        $this->position->setDiscount($discount);
        return $this;
    }

    public function setMarkup(Markup $markup): PositionBuilder
    {
        $this->position->setMarkup($markup);
        return $this;
    }

    public function setTax($tax): PositionBuilder
    {
        $this->position->setTax($tax);
        return $this;
    }

    public function setSection(Section $section = null): PositionBuilder
    {
        $this->position->setSection($section);
        return $this;
    }

    public function build(): Position
    {
        return $this->position;
    }
}