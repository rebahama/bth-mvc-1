<?php

namespace App\Card;

class CardGraphic extends Card
{
    public function getUnicode(): string
    {
        $suits = [
            'Hearts' => '♥',
            'Diamonds' => '♦',
            'Clubs' => '♣',
            'Spades' => '♠',
        ];

        return "{$this->value}{$suits[$this->suit]}";
    }
}
