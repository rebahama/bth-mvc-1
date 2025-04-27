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
            "suit" => $this->suit,
            "value" => $this->value,
        ];
    }

    public function getPoints(): int
    {
        $faceCards = ["J", "Q", "K"];
        if (in_array($this->value, $faceCards)) {
            return 10;
        }

        if ($this->value === "A") {
            return 1; //
        }

        return (int) $this->value;
    }

    public static function drawForBank(array $cards): int
    {
        $bankPoints = 0;
        foreach ($cards as $card) {
            $bankPoints += $card->getPoints();
        }

        return $bankPoints;
    }

    public static function bankStop(array $cards): bool
    {
        $bankPoints = self::drawForBank($cards);

        return ($bankPoints >= 17 && $bankPoints <= 21) || $bankPoints > 21;
    }

    public function determineWinner(
        int $bankPoints,
        int $playerPoints
    ): string {
        if ($playerPoints > 21) {
            return "Bank is winner! (Player got over 21)";
        }

        if ($bankPoints > 21) {
            return "Player wins! (Bank got over 21)";
        }

        if ($bankPoints >= $playerPoints) {
            return "Bank is winner!";
        }

        return "Player is winner!";
    }


    public function calculateTotalPoints(array $cards): int
    {
        $totalPoints = 0;
        $aces = 0;

        foreach ($cards as $card) {
            if ($card->getValue() === 'A') {
                $aces++;
                $totalPoints += 1;
                continue;
            }

            $totalPoints += $card->getPoints();
        }

        while ($aces > 0 && $totalPoints + 13 <= 21) {
            $totalPoints += 13;
            $aces--;
        }

        return $totalPoints;
    }

    public function getRemainingCards(DeckOfCards $deck): int
    {
        return count($deck->getCards());
    }

    public function drawCardFromDeck(DeckOfCards $deck): ?Card
    {
        return $deck->drawCard();
    }
}
