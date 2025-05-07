<?php

namespace App\DeckOfCards;
use App\Card\Card;
use App\Card\DeckOfCards;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class DeckOfCards
 */
class DeckOfCardsTest extends TestCase
{
    /**
    * Test that card deck is created and 52 cards are created
    */
   public function testGetCards(): void
   {

    $deck = new DeckOfCards(true);
    $cards = $deck->getCards();

    $this->assertNotNull($cards);
    $this->assertIsArray($cards);
    $this->assertCount(52, $cards);
   }
   /**
    * Test 3 cards are drawn and that the expected output is 3
    */
   public function testDrawCards(): void
    {
    $deck = new DeckOfCards(true);
    
   
    $drawn = $deck->draw(3);
    $this->assertCount(3, $drawn);

    }
    /**
    * Test that the drawn card draws 1 card from the deck    
    */
    public function testDrawCard(): void
    {
        $deck = new DeckOfCards();
        $initialCount = count($deck->getCards());

        $card = $deck->drawCard();

        $this->assertInstanceOf(Card::class, $card);
        $this->assertCount($initialCount - 1, $deck->getCards());
    }
    /**
    * Test that the deck is sorted by color and number
    */
    public function testSortByColorAndNumber(): void
    {
        $deck = new DeckOfCards(true);
        $deck->sortByColorAndNumber();
        $cards = $deck->getCards();

        $this->assertEquals('Hearts', $cards[0]->getSuit());
        $this->assertEquals('2', $cards[0]->getValue());
        $this->assertEquals('Diamonds', $cards[13]->getSuit());
        $this->assertEquals('2', $cards[13]->getValue());

        $this->assertEquals('Clubs', $cards[26]->getSuit());
        $this->assertEquals('2', $cards[26]->getValue());

        $this->assertEquals('Spades', $cards[39]->getSuit());
        $this->assertEquals('2', $cards[39]->getValue());
    }

   
}