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
        usort($this->cards, function ($firstCard, $secondCard) {
            $colorOrder = [
                'Clubs' => 'black',
                'Spades' => 'black',
                'Hearts' => 'red',
                'Diamonds' => 'red'
            ];

            $suitsOrder = ['Clubs', 'Spades', 'Hearts', 'Diamonds'];
            $valuesOrder = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'];

            $firstColor = $colorOrder[$firstCard->getSuit()];
            $secondColor = $colorOrder[$secondCard->getSuit()];

            if ($firstColor === $secondColor) {
                $firstSuitIndex = array_search($firstCard->getSuit(), $suitsOrder);
                $secondSuitIndex = array_search($secondCard->getSuit(), $suitsOrder);
                $firstValueIndex = array_search($firstCard->getValue(), $valuesOrder);
                $secondValueIndex = array_search($secondCard->getValue(), $valuesOrder);

                if ($firstSuitIndex === $secondSuitIndex) {
                    return $firstValueIndex - $secondValueIndex;
                }

                return $firstSuitIndex - $secondSuitIndex;
            }

            return ($firstColor === 'red') ? -1 : 1;
        });
    }
}
