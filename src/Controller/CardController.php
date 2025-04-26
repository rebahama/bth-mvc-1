<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Card\DeckOfCards;
use App\Card\Card;

class CardController extends AbstractController
{
    #[Route("/start/game", name: "card_game")]
    public function startgame(): Response
    {
        return $this->render("card/game.html.twig", []);
    }

    #[Route("/game", name: "card_play")]
    public function startplay(
        Request $request,
        SessionInterface $session
    ): Response {
        $deck = $session->get("deck", null);
        $gameStopped = $session->get("game_stopped", false);
        $drawnCards = $session->get("drawn_cards", []);
        $bankCards = $session->get("bank_cards", []);
        $totalPoints = Card::calculateTotalPoints($drawnCards);

        if (!$deck || $gameStopped) {
            $remaining = Card::getRemainingCards($deck);
            $bankPoints = $session->get("bank_points", 0);
            $winner = $session->get("winner", null);
            return $this->render("card/game_play.html.twig", [
                "cards" => $drawnCards,
                "points" => $totalPoints,
                "remaining" => $remaining,
                "bank_cards" => $bankCards,
                "bank_points" => $bankPoints,
                "game_stopped" => $gameStopped,
                "winner" => $winner,
            ]);
        }

        if (!$deck) {
            $deck = new DeckOfCards(true);
            $deck->shuffle();
            $session->set("deck", $deck);
            $session->set("drawn_cards", []);
        }

        $drawnCard = Card::drawCardFromDeck($deck);
        if ($drawnCard) {
            $drawnCards[] = $drawnCard;
            $session->set("drawn_cards", $drawnCards);
            $totalPoints = Card::calculateTotalPoints($drawnCards);
        }
        $remaining = Card::getRemainingCards($deck);

        return $this->render("card/game_play.html.twig", [
            "cards" => $drawnCards,
            "points" => $totalPoints,
            "remaining" => $remaining,
            "bank_cards" => $bankCards,
            "bank_points" => $session->get("bank_points", 0),
            "game_stopped" => $gameStopped,
            "winner" => $session->get("winner", null),
        ]);
    }

    #[Route("/game/stop", name: "card_stop")]
    public function stopGame(SessionInterface $session): Response
    {
        $session->set("game_stopped", true);

        $deck = $session->get("deck");
        $playerCards = $session->get("drawn_cards", []);

        $playerPoints = Card::calculateTotalPoints($playerCards);
        $bankCards = $deck->draw(2);
        $bankPoints = Card::calculateTotalPoints($bankCards);
        $maxDraws = 10;
        $draws = 0;
        while ($bankPoints < 17 && $draws < $maxDraws) {
            $newCard = $deck->drawCard();
            if ($newCard !== null) {
                $bankCards[] = $newCard;
                $draws++;
                $bankPoints = Card::calculateTotalPoints($bankCards);
            }
        }

        $bankPoints = Card::calculateTotalPoints($bankCards);

        $winner = Card::determineWinner($bankPoints, $playerPoints);

        $session->set("bank_cards", $bankCards);
        $session->set("bank_points", $bankPoints);
        $session->set("winner", $winner);
        return $this->redirectToRoute("card_play");
    }

    #[Route("/card/deck", name: "card_deck")]
    public function deck(): Response
    {
        $deck = new DeckOfCards(true);
        $deck->shuffle();

        $deck->sortByColorAndNumber();

        $cards = $deck->getCards();

        return $this->render("card/deck.html.twig", [
            "cards" => $cards,
        ]);
    }

    #[Route("/card/deck/shuffle", name: "card_shuffle")]
    public function shuffle(SessionInterface $session): Response
    {
        $session->clear();
        $this->addFlash("success", "Session have been cleared");

        $deck = new DeckOfCards(true);
        $deck->shuffle();
        $session->set("deck", $deck);
        $cards = $deck->getCards();

        return $this->render("card/shuffle.html.twig", [
            "cards" => $cards,
        ]);
    }

    #[Route("/card/deck/draw", name: "card_draw")]
    public function draw(SessionInterface $session): Response
    {
        if (!$session->has("deck")) {
            $deck = new DeckOfCards(true);
            $deck->shuffle();
            $session->set("deck", $deck);
        } else {
            $deck = $session->get("deck");
        }

        $drawnCards = $deck->draw(1);

        $remaining = count($deck->getCards());

        $session->set("deck", $deck);

        return $this->render("card/draw.html.twig", [
            "cards" => $drawnCards,
            "remaining" => $remaining,
        ]);
    }

    #[Route("/card/deck/draw/{number}", name: "card_draw_number")]
    public function drawNumber(int $number, SessionInterface $session): Response
    {
        if (!$session->has("deck")) {
            $deck = new DeckOfCards(true);
            $deck->shuffle();
            $session->set("deck", $deck);
        } else {
            $deck = $session->get("deck");
        }

        $drawnCards = $deck->draw($number);

        $remaining = count($deck->getCards());

        $session->set("deck", $deck);

        return $this->render("card/draw_number.html.twig", [
            "cards" => $drawnCards,
            "remaining" => $remaining,
            "requested" => $number,
        ]);
    }

    #[Route("/restart", name: "card_restart")]
    public function restartGame(SessionInterface $session): Response
    {
        $session->clear();
        $deck = new DeckOfCards(true);
        $deck->shuffle();
        $session->set("deck", $deck);

        return $this->redirectToRoute("card_game");
    }

    #[Route("/session/delete", name: "session_delete")]
    public function delete(SessionInterface $session): Response
    {
        $session->clear();

        return $this->redirectToRoute("card_deck");
    }

    #[Route("/session", name: "session_debug")]
    public function sessionDebug(SessionInterface $session): Response
    {
        $sessionData = $session->all();
        return $this->render("card/session_show.html.twig", [
            "sessionData" => $sessionData,
        ]);
    }
}
