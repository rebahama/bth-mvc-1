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
            'suit' => $this->suit,
            'value' => $this->value
        ];
    }

    public function getPoints(): int
    {
        $faceCards = ['J', 'Q', 'K'];
        if (in_array($this->value, $faceCards)) {
            return 10;
        }

        if ($this->value === 'A') {
            return 14;
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

    public static function shouldBankStop(array $cards): bool
    {
    $bankPoints = self::drawForBank($cards);

    return $bankPoints >= 17 && $bankPoints <= 21 || $bankPoints > 21;
    }

    
}
