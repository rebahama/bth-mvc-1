<?php
namespace App\Card;

class Card {
    public $suit;
    public $value;

    public function __construct($suit, $value) {
        $this->suit = $suit;
        $this->value = $value;
    }
}