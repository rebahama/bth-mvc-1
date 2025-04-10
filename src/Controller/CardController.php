<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Card\DeckOfCards;

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
    public function shuffle(SessionInterface $session): Response
    {
        $session->clear();
        $this->addFlash('success', 'Session have been cleared');

        $deck = new DeckOfCards(true);
        $deck->shuffle();
        $session->set('deck', $deck);
        $cards = $deck->getCards();

        return $this->render('card/shuffle.html.twig', [
            'cards' => $cards
        ]);
    }


    #[Route('/card/deck/draw', name: 'card_draw')]
    public function draw(SessionInterface $session): Response
    {
        if (!$session->has('deck')) {
            $deck = new DeckOfCards(true);
            $deck->shuffle();
            $session->set('deck', $deck);
        } else {
            $deck = $session->get('deck');
        }

        $drawnCards = $deck->draw(1);

        $remaining = count($deck->getCards());

        $session->set('deck', $deck);

        return $this->render('card/draw.html.twig', [
            'cards' => $drawnCards,
            'remaining' => $remaining
        ]);
    }


    #[Route('/card/deck/draw/{number}', name: 'card_draw_number')]
    public function drawNumber(int $number, SessionInterface $session): Response
    {
        if (!$session->has('deck')) {
            $deck = new DeckOfCards(true);
            $deck->shuffle();
            $session->set('deck', $deck);
        } else {
            $deck = $session->get('deck');
        }

        $drawnCards = $deck->draw($number);

        $remaining = count($deck->getCards());

        $session->set('deck', $deck);

        return $this->render('card/draw_number.html.twig', [
            'cards' => $drawnCards,
            'remaining' => $remaining,
            'requested' => $number
        ]);
    }

    #[Route('/session/delete', name: 'session_delete')]
    public function delete(SessionInterface $session): Response
    {
        $session->clear();

        return $this->redirectToRoute('card_deck');
    }

    #[Route('/session', name: 'session_debug')]
    public function sessionDebug(SessionInterface $session): Response
    {
        $sessionData = $session->all();
        return $this->render('card/session_show.html.twig', [
            'sessionData' => $sessionData
        ]);
    }

}
