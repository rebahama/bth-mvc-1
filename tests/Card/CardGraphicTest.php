<?php

namespace App\CardGraphic;
use App\Card\CardGraphic;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardGraphic class
 */
class CardGraphicTest extends TestCase
{
    public function testCardGraphicUnicode(): void
    /**
     * Testing so that the graphic is outputed in the correct symbols and letters
     * 
     */
    {
        $card = new CardGraphic('Hearts', 'Q');
        $newCard = new CardGraphic('Hearts', 'K');
        $unicodeOne = $card->getUnicode();
        $unicodeTwo = $newCard->getUnicode();

        $this->assertEquals('Q♥', $unicodeOne);
        $this->assertEquals('K♥', $unicodeTwo);
    }
}