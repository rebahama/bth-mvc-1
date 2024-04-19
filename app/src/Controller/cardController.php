<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Card\Deck;

class cardController extends AbstractController
{

    #[Route("/card", name: "card")]
    public function card(): Response
    {
        return $this->render('cards/first.html.twig', [
        ]);
    }
    #[Route("/session", name: "session")]
    public function homesession(SessionInterface $session): Response
    {
        // Hämta allt innehåll från sessionen
        $sessionData = $session->all();
        $session->set('test_key', 'test_value');


        return $this->render('cards/home.html.twig', [
            'sessionData' => $sessionData,
        ]);
    }
    #[Route("/session/delete", name: "delete_session_item")]
    public function deleteSessionItem(SessionInterface $session): Response
    {

        $session->clear();

        $this->addFlash('success', 'Sessionen har raderats.');

        return $this->redirectToRoute('session');
    }
    #[Route("/card/deck", name: "card_deck")]
    public function cardDeck(): Response
    {
        $deck = new Deck();
        $deck->sortBySuitAndValue();

        return $this->render('cards/deck.html.twig', [
            'deck' => $deck,
        ]);
    }
    #[Route("/card/deck/shuffle", name: "card_shuffle")]
    public function cardShuffle(SessionInterface $session): Response
    {
        $deck = new Deck();
        $session->remove('deck'); // Remove the 'deck' key from the session
        $deck->randomCard();

        return $this->render('cards/shuffle.html.twig', [
            'deck' => $deck,
        ]);
    }
    #[Route("/card/deck/draw", name: "card_draw")]
    public function cardDraw(SessionInterface $session): Response
    {
        $deck = $this->getDeckFromSession($session);

        if ($deck === null || $deck->getNumberOfCardsLeft() === 0) {

            $deck = new Deck();
            $deck->randomCard();
            $this->saveDeckToSession($deck, $session);
        }

        $drawnCard = $deck->drawCard();

        // Save updated deck to session
        $this->saveDeckToSession($deck, $session);

        return $this->render('cards/draw.html.twig', [
            'drawnCard' => $drawnCard,
            'remainingCards' => $deck->getNumberOfCardsLeft(),
        ]);
    }

    private function getDeckFromSession(SessionInterface $session): ?Deck
    {
        return $session->get('deck');
    }

    private function saveDeckToSession(Deck $deck, SessionInterface $session): void
    {
        $session->set('deck', $deck);
    }
    #[Route("/card/deck/draw/{number}", name: "card_deck_draw")]
    public function cardDeckDraw(int $number, SessionInterface $session): Response
    {
        // Get the deck from session
        $deck = $this->getDeckFromSession($session);

        // If deck is empty or not in session, create a new one
        if ($deck === null || $deck->getNumberOfCardsLeft() === 0) {
            $deck = new Deck();
            $deck->randomCard();
            $this->saveDeckToSession($deck, $session);
        }

        // Draw specified number of cards from the deck
        $drawnCards = [];
        for ($i = 0; $i < $number; $i++) {
            $card = $deck->drawCard();
            if ($card !== null) {
                $drawnCards[] = $card;
            } else {
                break;
            }
        }

        $this->saveDeckToSession($deck, $session);
        return $this->render('cards/draw_number.html.twig', [
            'drawnCards' => $drawnCards,
            'remainingCards' => $deck->getNumberOfCardsLeft(),
        ]);
    }

}
