<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Card\DeckOfCards;
use Symfony\Component\Routing\Annotation\Route;

class apiData
{
    #[Route("/api/quote", name: "api_name")]
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

        return new JsonResponse($data);
    }


    #[Route("/api/deck", name: "api_deck", methods: ["GET"])]
    public function jsonDeck(): Response
    {
        $deck = new DeckOfCards(true);

        $deck->sortByColorAndNumber();

        $cards = $deck->getCards();

        $data = $cards;

        return new JsonResponse($data);
    }

    #[Route("/api/deck/shuffle", name: "api_shuffle")]
    public function jsonShuffle(SessionInterface $session): Response
    {
        $deck = new DeckOfCards(true);
        $deck->shuffle();

        $session->set('deck', $deck);

        $cards = $deck->getCards();

        return new JsonResponse($cards);
    }

    #[Route("/api/deck/draw/{number}", name: "api_draw_number")]
    public function draw(int $number, SessionInterface $session): JsonResponse
    {
        $deck = $session->get('deck');

        if (!$deck) {
            $deck = new DeckOfCards(true);
            $deck->shuffle();
            $session->set('deck', $deck);
        }

        $drawnCards = $deck->draw($number);

        $session->set('deck', $deck);

        $remaining = count($deck->getCards());

        $data = [
            'drawn_cards' => $drawnCards,
            'remaining' => $remaining
        ];

        return new JsonResponse($data);
    }

}
