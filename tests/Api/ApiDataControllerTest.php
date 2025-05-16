<?php

namespace App\Controller;
use App\Controller\ApiGameData;
use App\Card\DeckOfCards;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for APi class
 */
class ApiDatControllerTest extends TestCase
{


     public function testJsonDeck(): void
     /**
      * Testing for the card of deck in json format.
      */
    {
        $controller = new ApiGameData;
        $response = $controller->jsonDeck();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $data = json_decode($response->getContent(), true);
        $this->assertIsArray($data);

        $firstCard = $data[0] ?? null;
        $this->assertNotNull($firstCard);

        $this->assertArrayHasKey('suit', $firstCard);
        $this->assertArrayHasKey('value', $firstCard);
    }


    public function testJsonShuffle(): void
    /**
     * Testing the shuffle in the json format.
     */
    {
    $session = $this->createMock(SessionInterface::class);

    $session->expects($this->once())
        ->method('set')
        ->with(
            'deck',
            $this->isInstanceOf(DeckOfCards::class)
        );

    $controller = new ApiGameData();

    $response = $controller->jsonShuffle($session);

    $this->assertInstanceOf(JsonResponse::class, $response);

    $data = json_decode($response->getContent(), true);

    $this->assertArrayHasKey('message', $data);
    $this->assertEquals('Session has been cleared!', $data['message']);
    $this->assertArrayHasKey('cards', $data);
    $this->assertIsArray($data['cards']);
    }

    public function testDrawCard(): void
    {
    /**
     * Testing drawing of card in json format.
     */

    $deck = new DeckOfCards(true);
    $deck->draw(1);
    count($deck->getCards());

    $session = $this->createMock(SessionInterface::class);

    $session->expects($this->once())
        ->method('get')
        ->with('deck', $this->isInstanceOf(DeckOfCards::class))
        ->willReturn((new DeckOfCards(true)));

    $session->expects($this->once())
        ->method('set')
        ->with(
            $this->equalTo('deck'),
            $this->isInstanceOf(DeckOfCards::class)
        );

    $controller = new ApiGameData();

    $response = $controller->drawCard($session);

    $this->assertInstanceOf(JsonResponse::class, $response);

    $data = json_decode($response->getContent(), true);

    $this->assertArrayHasKey('card', $data);
    $this->assertArrayHasKey('remaining', $data);

    $this->assertIsArray($data['card']);
    $this->assertIsInt($data['remaining']);
    }

    public function testDrawMultipleCards(): void
    {
    /**
     * Testing the drawing of cards but with a random number of 3.
     */
    $drawCount = 3;
    $deck = new DeckOfCards(true);

    $session = $this->createMock(SessionInterface::class);

    $session->expects($this->once())
        ->method('get')
        ->with('deck')
        ->willReturn($deck);

    $session->expects($this->once())
        ->method('set')
        ->with(
            $this->equalTo('deck'),
            $this->isInstanceOf(DeckOfCards::class)
        );

    $controller = new ApiGameData();

    $response = $controller->draw($drawCount, $session);

    $this->assertInstanceOf(JsonResponse::class, $response);

    $data = json_decode($response->getContent(), true);

    $this->assertArrayHasKey('drawn_cards', $data);
    $this->assertArrayHasKey('remaining', $data);

    $this->assertCount($drawCount, $data['drawn_cards']);

    $this->assertIsInt($data['remaining']);
    }
   
}