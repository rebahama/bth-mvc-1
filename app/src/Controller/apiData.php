<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Card\Deck;

class apiData
{
    #[Route("/api/quote")]
    public function jsonNumber(): Response
    {
        date_default_timezone_set('Europe/Stockholm');

        $quotes = [
            "Jag är stark idag!!",
            "Jag är redo idag!!",
            "Jag är effektiv idag!!"
        ];

        $randomQuote = $quotes[array_rand($quotes)];


        $todayDate = date("Y-m-d");


        $timestamp = time();

        $time = date("H:i:s", $timestamp);

        $data = [
            'quote' => $randomQuote,
            'date' => $todayDate,
            'time' => $time
        ];

        // Return the JSON response
        return new JsonResponse($data);
    }

    #[Route("/api/deck", name: "api_deck")]
    public function apiDeck(): JsonResponse
    {
        $deck = new Deck();
        $deck->sortBySuitAndValue();

        return new JsonResponse($deck);
    }

    #[Route("/api/deck/shuffle", name: "api_shuffle")]
    public function apiShuffle(): JsonResponse
    {

        $deck = new Deck();
        $deck->randomCard();
        // Returnera JSON-responsen med kortleken
        return new JsonResponse($deck);
    }

    #[Route("/api/deck/draw", name: "api_draw")]
    public function apiDraw(SessionInterface $session): JsonResponse
    {
        // Hämta kortleken från sessionen om den finns, annars skapa en ny kortlek
        $deck = $session->get('deck');
        if ($deck === null) {
            $deck = new Deck();
            $session->set('deck', $deck);
        }

        // Dra ett kort från kortleken
        $drawnCard = $deck->drawCard();


        // Hämta antalet kvarvarande kort i kortleken
        $remainingCards = $deck->getNumberOfCardsLeft();

        // Returnera JSON-responsen med kortleken och antalet kvarvarande kort
        return new JsonResponse([
            'drawnCard' => $drawnCard,
            'remainingCards' => $remainingCards,

        ]);
    }

    #[Route("/api/deck/draw/{number}", name: "api_deck_draw")]
    public function apiDeckDraw(int $number, SessionInterface $session): JsonResponse
    {
        // Hämta kortleken från sessionen om den finns, annars skapa en ny kortlek
        $deck = $session->get('deck');
        if ($deck === null) {
            $deck = new Deck();
            $session->set('deck', $deck);
        }

        // Dra ett eller flera kort från kortleken
        $drawnCards = [];
        for ($i = 0; $i < $number; $i++) {
            $drawnCard = $deck->drawCard();
            if ($drawnCard !== null) {
                $drawnCards[] = $drawnCard;
            } else {
                break; // Avbryt loopen om det inte finns tillräckligt med kort kvar i kortleken
            }
        }

        // Hämta antalet kvarvarande kort i kortleken
        $remainingCards = $deck->getNumberOfCardsLeft();

        // Returnera JSON-responsen med de dragna korten och antalet kvarvarande kort
        return new JsonResponse([
            'drawnCards' => $drawnCards,
            'remainingCards' => $remainingCards,
        ]);
    }

}
