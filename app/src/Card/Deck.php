<?php
namespace App\Card;

class Deck {
    public $cards = [];

    public function __construct() {
        $suits = ['♥', '♠', '♦', '♣']; // Hearts, Spades, Diamonds, Clubs
        $values = ['2', '3', '4', '5', '6', '7', '8', '9', '10', '🂫', '🂭', '🂮']; // Larger representations of Jack, Queen, King

        foreach ($suits as $suit) {
            foreach ($values as $value) {
                $this->cards[] = new Card($suit, $value);
            }
        }
    }

    public function sortBySuitAndValue() {
        usort($this->cards, function($a, $b) {
            if ($a->suit == $b->suit) {
                return strcmp($a->value, $b->value);
            }
            return strcmp($a->suit, $b->suit);
        });
    }
}