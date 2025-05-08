<?php

namespace App\Card;

/**
 * Class for the Card
 * Diffrent methods when playing 21, all the methods have description of their functionality.
 *
 */
class Card implements \JsonSerializable
{
    protected string $suit;
    protected string $value;

    public function __construct(string $suit, string $value)
    {
        $this->suit = $suit;
        $this->value = $value;
    }
    /**
     * Get the suit of the card.
     * The suit of the card (e.g., "Hearts", "Spades", "Clubs", "Diamonds").
     */
    public function getSuit(): string
    {
        return $this->suit;
    }
    /**
     * Get the value of card
     *
     * @return string The suit of the card (e.g. "1", "3", "5").
     */
    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return "{$this->value} of {$this->suit}";
    }
    /**
     * Get the array value of the serialized suit and value
     * Will display array and show the suit and value in the api page
     */
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
            return 1;
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

    /**
     * Determines who the winner is based on the points
     * If the player scores higher then bank then a message will be outputed
     * If bank scores higher then player then another message will be shown.
     */
    public function determineWinner(int $bankPoints, int $playerPoints): string
    {
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
    /**
     * Calculates ace to be 14 or 1
     * Depending on the score of the player or bank ace will be either 1 or 14,
     */
    public function calculateTotalPoints(array $cards): int
    {
        $totalPoints = 0;
        $aces = 0;

        foreach ($cards as $card) {
            if ($card->getValue() === "A") {
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
    /**
     * Shows the remaining cards number
     * Will display the number of reamaning cards left from the deck.
     */
    public function getRemainingCards(DeckOfCards $deck): int
    {
        return count($deck->getCards());
    }
    /**
     * Draws the card from the deck
     *
     */
    public function drawCardFromDeck(DeckOfCards $deck): ?Card
    {
        return $deck->drawCard();
    }
    /**
     * Draws card for the bank untill 17
     * Bank will draw card untill atleast the number 17 is meet.
     */
    public function bankDraw(
        DeckOfCards $deck,
        array &$bankCards,
        int $maxDraws = 10,
        int $minPoints = 17
    ): array {
        $draws = 0;
        $bankPoints = $this->calculateTotalPoints($bankCards);

        while ($bankPoints < $minPoints && $draws < $maxDraws) {
            $newCard = $deck->drawCard();
            if ($newCard !== null) {
                $bankCards[] = $newCard;
                $draws++;
                $bankPoints = $this->calculateTotalPoints($bankCards);
            }
        }

        return $bankCards;
    }
}
