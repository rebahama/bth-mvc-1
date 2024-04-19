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
   
}
