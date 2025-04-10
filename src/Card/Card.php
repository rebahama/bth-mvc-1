<?php

namespace App\Card;

class Card implements \JsonSerializable
{
    protected string $suit;
    protected string $value;

    public function __construct(string $suit, string $value)
    {
        $this->suit = $suit;
        $this->value = $value;
    }

    public function getSuit(): string
    {
        return $this->suit;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return "{$this->value} of {$this->suit}";
    }


     public function jsonSerialize(): mixed
     {
         return [
             'suit' => $this->suit,
             'value' => $this->value
         ];
     }
}
