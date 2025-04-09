<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Card\DeckOfCards;
use App\Card\Hand;

class CardController extends AbstractController
{
    #[Route('/card/deck', name: 'card_deck')]
    public function deck(): Response
    {
        $deck = new DeckOfCards(true);
        $deck->shuffle();

        $deck->sortByColorAndNumber();
    
        $cards = $deck->getCards();
    
        return $this->render('card/deck.html.twig', [
            'cards' => $cards
        ]);
    }

    #[Route('/card/deck/shuffle', name: 'card_shuffle')]
    public function shuffle(): Response
    {
        $deck = new DeckOfCards(true);
        $deck->shuffle();
    
        $cards = $deck->getCards();
    
        return $this->render('card/shuffle.html.twig', [
            'cards' => $cards
        ]);
    }
    
}