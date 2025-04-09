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
}
