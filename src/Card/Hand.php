<?php

namespace App\Card;

class Hand
{
    /** @var Card[] */
    private array $hand = [];

    public function add(Card $card): void
    {
        $this->hand[] = $card;
    }

    public function getCards(): array
    {
        return $this->hand;
    }

    public function clear(): void
    {
        $this->hand = [];
    }
}
