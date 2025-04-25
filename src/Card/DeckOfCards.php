<?php

namespace App\Card;

class DeckOfCards
{
    /** @var Card[] */
    private array $cards = [];

    public function __construct(bool $graphic = false)
    {
        $suits = ['Hearts', 'Diamonds', 'Clubs', 'Spades'];
        $values = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'];

        foreach ($suits as $suit) {
            foreach ($values as $value) {
                $this->cards[] = $graphic
                    ? new CardGraphic($suit, $value)
                    : new Card($suit, $value);
            }
        }
    }

    public function shuffle(): void
    {
        shuffle($this->cards);
    }

    public function draw(int $number = 1): array
    {
        return array_splice($this->cards, 0, $number);
    }

    public function getCards(): array
    {
        return $this->cards;
    }

    public function drawCard(): ?Card
    {
        if (count($this->cards) > 0) {
            return array_shift($this->cards);
        }

        return null;
    }


    public function sortByColorAndNumber(): void
    {
        usort($this->cards, function ($a, $b) {
            $colorOrder = [
                'Clubs' => 'black',
                'Spades' => 'black',
                'Hearts' => 'red',
                'Diamonds' => 'red'
            ];

            $suitsOrder = ['Clubs', 'Spades', 'Hearts', 'Diamonds'];
            $valuesOrder = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'];

            $aColor = $colorOrder[$a->getSuit()];
            $bColor = $colorOrder[$b->getSuit()];

            if ($aColor === $bColor) {
                $aSuitIndex = array_search($a->getSuit(), $suitsOrder);
                $bSuitIndex = array_search($b->getSuit(), $suitsOrder);
                $aValueIndex = array_search($a->getValue(), $valuesOrder);
                $bValueIndex = array_search($b->getValue(), $valuesOrder);

                if ($aSuitIndex === $bSuitIndex) {
                    return $aValueIndex - $bValueIndex;
                }

                return $aSuitIndex - $bSuitIndex;
            }


            return ($aColor === 'red') ? -1 : 1;
        });
    }
}
