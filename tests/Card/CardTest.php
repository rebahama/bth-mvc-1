<?php

namespace App\Card;
use App\Card\Card;
use App\Card\DeckOfCards;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card
 */
class CardTest extends TestCase
{
    public function testCreateCard()
    /**
     * Test if the class returns the suit and value
     */
    {
        $card = new Card('Hearts', 'A');

        $this->assertInstanceOf(Card::class, $card);
        $this->assertEquals('Hearts', $card->getSuit());

        $this->assertEquals('A', $card->getValue());
    }
     /**
     * Testing what the banks draws and show points
     */
    public function testDrawForBank(): void
    {
        $card = new Card("", "");

        $card1 = new Card('Hearts', '10');
        $card2 = new Card('Clubs', 'K');
        $card3 = new Card('Spades', 'A');
    
        $cards = [$card1, $card2, $card3];
        $totalPoints = $card->drawForBank($cards);
        $this->assertEquals(21, $totalPoints); 

    }
    /**
     * Testing the bank points matches the expected outcome when bank wins
     */
    public function testWinnerBank(): void{
        $card = new Card("", "");
        $bankPoints = 20;
        $playerPoints = 18;
        $winnerMessage = $card->determineWinner($bankPoints, $playerPoints);
        $this->assertEquals("Bank is winner!", $winnerMessage);

    }
    /**
     * Test that ace outputs 1 when points goes over 21, if not ace is 14
     */
    public function testCalculateTotalPointsWithAceAsOne(): void
    {
        $card1 = new Card('Hearts', 'A');
        $card2 = new Card('Spades', '7');
        $card3 = new Card('Diamonds', '3');

        $cards = [$card1, $card2, $card3];

        $cardInstance = new Card('', '');
        $total = $cardInstance->calculateTotalPoints($cards);
        $this->assertEquals(11, $total);
    }
    /**
     * Test that there are 52 cards in the deck
     */
    public function testRemainingCards(): void{
        $deck = new DeckOfCards(true);
        $card = new Card('Hearts', 'A');
        $remaining = $card->getRemainingCards($deck);

        $this->assertEquals(52, $remaining);

    }

    /**
     * Test that the cards are drawn from the deck
     */
    public function testDrawCardFromDeckReturnsCard(): void
    {
        $deck = new DeckOfCards();
        $deck->shuffle();

        $cardHelper = new Card('', '');

        $drawnCard = $cardHelper->drawCardFromDeck($deck);

        $this->assertInstanceOf(Card::class, $drawnCard);
        $this->assertNotNull($drawnCard);
    }
    /**
     * Test that the bank dont stop unntil 17
     */
    public function testBankDrawStopsAtMinPoints(): void
    {
        $deck = new DeckOfCards();
        $deck->shuffle();
        $cardHelper = new Card('', '');
        $bankCards = [];
        $resultCards = $cardHelper->bankDraw($deck, $bankCards);
        $totalPoints = $cardHelper->calculateTotalPoints($resultCards);
        $this->assertGreaterThanOrEqual(17, $totalPoints);
        foreach ($resultCards as $card) {
            $this->assertInstanceOf(Card::class, $card);
        }
    }
   
}