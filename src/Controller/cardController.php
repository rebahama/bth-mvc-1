<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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




    #[Route("/card/game/draw/", name: "card_deck_draw_game")]public function cardGame(Request $request, SessionInterface $session): Response
    {
        // Initialize the number from the session, defaulting to 1 if not set
        $number = $session->get('draw_number', 1);
    
        // Get the deck from session
        $deck = $this->getDeckFromSession($session);
    
        // If deck is empty or not in session, create a new one
        if ($deck === null || $deck->getNumberOfCardsLeft() === 0) {
            $deck = new Deck();
            $deck->randomCard();
            $this->saveDeckToSession($deck, $session);
        }
    
        // Retrieve previously drawn cards from session
        $drawnCards = $session->get('drawn_cards', []);
    
        // Initialize opponentDrawnCards as an empty array
        $opponentDrawnCards = [];
    
        // Initialize opponentSum to zero
        $opponentSum = 0;
    
        // Initialize the result
        $result = '';
    
        // Form for player to draw a card
        $playerDrawForm = $this->createFormBuilder()
            ->add('drawButton', SubmitType::class, ['label' => 'Draw Card'])
            ->getForm();
    
        $playerDrawForm->handleRequest($request);
    
        // Handle player's draw action
        if ($playerDrawForm->isSubmitted() && $playerDrawForm->isValid()) {
            // Draw a card for the player
            $playerCard = $deck->drawCard();
            if ($playerCard !== null) {
                // Add the player's newly drawn card to the existing drawn cards
                $drawnCards[] = $playerCard;
                // Recalculate the sum of the player's cards
                $sumOfPlayerCards = 0;
                foreach ($drawnCards as $card) {
                    $numericValue = $deck->getNumericValue($card->value);
                    $sumOfPlayerCards += $numericValue;
                }
                // Check if the player busts
                $isPlayerBust = $sumOfPlayerCards > 21;
                if ($isPlayerBust) {
                    $result = 'You lose!';
                    // Simulate opponent's moves even if player goes bust
                    while ($opponentSum < 17) {
                        $opponentCard = $deck->drawCard();
                        if ($opponentCard !== null) {
                            $opponentDrawnCards[] = $opponentCard;
                            $opponentSum += $deck->getNumericValue($opponentCard->value);
                        } else {
                            break;
                        }
                    }
                    // Determine if opponent has gone bust
                    $isOpponentBust = $opponentSum > 21;
                }
            }
            // Save the updated drawn cards to session
            $session->set('drawn_cards', $drawnCards);
        }
    
        // Form to clear the session
        $clearSessionForm = $this->createFormBuilder()
            ->add('clearSessionButton', SubmitType::class, ['label' => 'Clear Session'])
            ->getForm();
    
        $clearSessionForm->handleRequest($request);
    
        if ($clearSessionForm->isSubmitted() && $clearSessionForm->isValid()) {
            // Clear session
            $session->clear();
            // Redirect or render a response after clearing the session
            return $this->redirectToRoute('card_deck_draw_game'); // Redirect back to the same page
        }
    
        // Render the template with the forms and results
        return $this->render('cards/game.html.twig', [
            'playerDrawnCards' => $drawnCards,
            'opponentDrawnCards' => $opponentDrawnCards,
            'remainingCards' => $deck->getNumberOfCardsLeft(),
            'result' => $result,
            'sumOfPlayerCards' => $sumOfPlayerCards ?? 0,
            'opponentSum' => $opponentSum,
            'playerDrawForm' => $playerDrawForm->createView(),
            'clearSessionForm' => $clearSessionForm->createView(),
            'isPlayerBust' => $isPlayerBust ?? false,
            'isOpponentBust' => $isOpponentBust ?? false,
        ]);
    }




    
    #[Route("/game", name: "game")]
    public function game(): Response
    {
        return $this->render('cards/play_game.html.twig', [
        ]);
    }
    

}
