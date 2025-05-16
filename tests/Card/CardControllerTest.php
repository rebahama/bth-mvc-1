<?php

namespace App\Card;
use App\Controller\CardController;
use App\Card\DeckOfCards;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card
 */
class CardControllerTest extends TestCase
{

     public function testStartGame(): void
     /**
      * Testing and mocking the start of the game.
      */
    {
        $session = $this->createMock(SessionInterface::class);

        $session->expects($this->once())
            ->method('has')
            ->with('deck')
            ->willReturn(false);

        $session->expects($this->exactly(3))
            ->method('set')
            ->withConsecutive(
                [$this->equalTo('deck'), $this->isInstanceOf(DeckOfCards::class)],
                ['drawn_cards', []],
                ['game_stopped', false]
            );

        $controller = $this->getMockBuilder(CardController::class)
            ->onlyMethods(['render'])
            ->getMock();

        $controller->expects($this->once())
            ->method('render')
            ->with('card/game.html.twig')
            ->willReturn(new Response('rendered content'));

        $response = $controller->startgame($session);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('rendered content', $response->getContent());
    }
    
     public function testRestartGameClearsSessionAndRedirects(): void
     /**
      * Testing to se if the route clears and redirects.
      */
    {
        $sessionMock = $this->createMock(SessionInterface::class);

        $sessionMock->expects($this->once())->method('clear');
        $sessionMock->expects($this->once())
            ->method('set')
            ->with(
                $this->equalTo('deck'),
                $this->isInstanceOf(DeckOfCards::class)
            );

        $controller = $this->getMockBuilder(CardController::class)
            ->onlyMethods(['redirectToRoute'])
            ->disableOriginalConstructor()
            ->getMock();

        $controller->expects($this->once())
            ->method('redirectToRoute')
            ->with('card_game')
            ->willReturn(new RedirectResponse('/game'));

        $response = $controller->restartGame($sessionMock);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertSame('/game', $response->getTargetUrl());
    }

      public function testDeleteClears(): void
      /**
       * Test to se if session deletes and clears
       */
    {
        $sessionMock = $this->createMock(SessionInterface::class);
        $sessionMock->expects($this->once())->method('clear');

        $controller = $this->getMockBuilder(CardController::class)
            ->onlyMethods(['redirectToRoute'])
            ->disableOriginalConstructor()
            ->getMock();

        $controller->expects($this->once())
            ->method('redirectToRoute')
            ->with('card_deck')
            ->willReturn(new RedirectResponse('/card/deck'));

        $response = $controller->delete($sessionMock);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertSame('/card/deck', $response->getTargetUrl());
    }

   
}