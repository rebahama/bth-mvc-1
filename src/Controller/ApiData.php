<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\LibraryRepository;
use App\Card\DeckOfCards;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Card\Card;
use Symfony\Component\Routing\Annotation\Route;

class ApiData extends AbstractController
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
    public function jsonShuffle(SessionInterface $session): JsonResponse
    {
        $deck = new DeckOfCards(true);
        $deck->shuffle();

        $session->set('deck', $deck);

        $message = "Session has been cleared!";

        $cards = $deck->getCards();

        return new JsonResponse([
            'message' => $message,
            'cards' => $cards
        ]);
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

    #[Route("/api/deck/draw", name: "api_draw")]
    public function drawCard(SessionInterface $session): JsonResponse
    {

        $deck = $session->get('deck', new DeckOfCards(true));

        $drawnCard = $deck->draw(1);


        $remaining = count($deck->getCards());

        $session->set('deck', $deck);

        return new JsonResponse([
            'card' => $drawnCard,
            'remaining' => $remaining
        ]);
    }

    #[Route("/api/game", name: "api_game", methods: ["GET"])]
    public function jsonGame(SessionInterface $session): JsonResponse
    {
        $deck = $session->get('deck', new DeckOfCards(true));
        $card = new Card('', '');
        $drawnCards = $session->get('drawn_cards', []);
        $bankCards = $session->get('bank_cards', []);
        $bankPoints = $session->get('bank_points', 0);
        $winner = $session->get('winner', null);
        $gameStopped = $session->get('game_stopped', false);
        $totalPoints = $card->calculateTotalPoints($drawnCards);
        $reamaining = $card->getRemainingCards($deck);

        $data = [
            'drawn_cards' => $drawnCards,
            'player_points' => $totalPoints,
            'bank_cards' => $bankCards,
            'bank_points' => $bankPoints,
            'game_stopped' => $gameStopped,
            'winner' => $winner,
            'remaining' => $reamaining
        ];

        return new JsonResponse($data);
    }

    #[Route("/api/library/books", name: "api_bok_view", methods: ["GET"])]
    public function jsonBooks(LibraryRepository $libraryRepository): JsonResponse
    {
        $libraries = $libraryRepository->findAll();

        $data = [];

        foreach ($libraries as $library) {
            $data[] = [
                'id' => $library->getId(),
                'title' => $library->getTitle(),
                'isbn' => $library->getIsbn(),
                'author' => $library->getAuthor(),
                'image_path' => $library->getImagePath(),
            ];
        }

        return $this->json($data);
    }

    #[Route("/api/library/book/{isbn}", name: "api_book_view_isbn", methods: ["GET"])]
    public function jsonBook(LibraryRepository $libraryRepository, string $isbn): JsonResponse
    {
        $library = $libraryRepository->findOneByIsbn($isbn);

        if (!$library) {
            return $this->json(['error' => 'Book not found'], 404);
        }

        $data = [
            'id' => $library->getId(),
            'title' => $library->getTitle(),
            'isbn' => $library->getIsbn(),
            'author' => $library->getAuthor(),
            'image_path' => $library->getImagePath(),
        ];

        return $this->json($data);
    }

}
