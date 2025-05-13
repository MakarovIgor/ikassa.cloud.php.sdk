<?php

namespace igormakarov\IKassa\ApiClient\Models\Position;

use igormakarov\IKassa\ApiClient\Models\Modifier\Discount;
use igormakarov\IKassa\ApiClient\Models\Modifier\Markup;
use InvalidArgumentException;

class Position
{
    private string $name;
    private int $price;
    private int $quantity;

    private ?Discount $discount = null;
    private ?Markup $markup = null;

    protected string $tax = "";

    private ?Section $section = null;

    private string $thirdPartyUNP;



    public function __construct(string $name, int $price, int $quantity)
    {
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    public function setTax(string $tax)
    {
        if (!in_array($tax, TaxTypes::toArray())) {
            throw new InvalidArgumentException("Tax types are not allowed");
        }
        $this->tax = $tax;
    }

    public function setSection(Section $section)
    {
        $this->section = $section;
    }

    public function setDiscount(Discount $discount)
    {
        $this->discount = $discount;
    }

    public function setMarkup(Markup $markup)
    {
        $this->markup = $markup;
    }

    public function setThirdPartyUNP(string $thirdPartyUNP)
    {
        $this->thirdPartyUNP = $thirdPartyUNP;
    }

    public function toArray(): array
    {
        $array = [
            'name' => $this->name,
            'price' => $this->price,
            'quantity' => $this->quantity,
        ];

        if (!empty($this->tax)) {
            $array['tax'] = $this->tax;
        }

        if ($this->section != null) {
            $array['section'] = $this->section->toArray();
        }

        if (!empty($this->thirdPartyUNP)) {
            $array['tpTaxNumber'] = $this->thirdPartyUNP;
        }

        if (!empty($this->discount)) {
            $array['modifiers'][] = $this->discount->toModifier()->toArray();
        }

        if (!empty($this->markup)) {
            $array['modifiers'][] = $this->markup->toModifier()->toArray();
        }

        return $array;
    }
}