<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Card\DeckOfCards;
use App\Card\Hand;

class CardController extends AbstractController
{
    #[Route('/card/draw', name: 'card_draw')]
    public function draw(): Response
    {
        $deck = new DeckOfCards(true);
        $deck->shuffle();

        $hand = new Hand();
        foreach ($deck->draw(5) as $card) {
            $hand->add($card);
        }

        $cards = array_map(fn($c) => (string) $c, $hand->getCards());

        return $this->json([
            'hand' => $cards
        ]);
    }
}

