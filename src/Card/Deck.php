<?php

namespace App\Card;

class Deck
{
    public $cards = [];

    public function __construct()
    {
        $suits = ['♥', '♠', '♦', '♣']; // Hearts, Spades, Diamonds, Clubs
        $values = [2, 3, 4, 5, 6, 7, 8, 9, 10, 'JACK:🂫', 'QUEEN:🂭', 'KING:🂮', 'ACE:🃑']; // Larger representations of Jack, Queen, King, and Ace


        foreach ($suits as $suit) {
            foreach ($values as $value) {
                $this->cards[] = new Card($suit, $value);
            }
        }
        
    }


    public function sortBySuitAndValue()
    {
        usort($this->cards, function ($a, $b) {
            // Define the order of card values
            $valueOrder = ['3', '3', '4', '5', '6', '7', '8', '9', '10', 'JACK:🂫', 'QUEEN:🂭', 'KING:🂮', 'ACE:🃑'];
            $aIndex = array_search($a->value, $valueOrder);
            $bIndex = array_search($b->value, $valueOrder);

            // If both cards have the same suit, compare their values
            if ($a->suit == $b->suit) {
                return $aIndex - $bIndex; // Compare based on the order index
            }

            // If the suits are different, compare their suits
            return strcmp($a->suit, $b->suit);
        });
    }

    public function randomCard()
    {
        shuffle($this->cards);
    }

    public function drawCard()
    {
        return array_shift($this->cards);
    }

    public function getNumberOfCardsLeft()
    {
        return count($this->cards);
    }
    
    public function sumCardValues()
    {
        $sum = 0;
        foreach ($this->cards as $card) {
            // Check if $card is an object before accessing its properties
            if (is_object($card)) {
                // Convert non-numeric card values to their corresponding numeric values
                $numericValue = $this->getNumericValue($card->value);
                $sum += $numericValue;
            }
        }
        return $sum;
    }
    
    // Helper function to convert card values to numeric values
    public function getNumericValue($value)
    {
        switch ($value) {
            case 'JACK:🂫':
            case 'QUEEN:🂭':
            case 'KING:🂮':
                return 10;
            case 'ACE:🃑':
                return 11; // Assuming Ace can be either 1 or 11
            default:
                return (int)$value;
        }
    }
   
   
    

    
}
