<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\LibraryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
