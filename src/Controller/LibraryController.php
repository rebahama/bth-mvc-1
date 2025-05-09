<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Library;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\LibraryRepository;

final class LibraryController extends AbstractController
{
    #[Route('/library', name: 'app_library')]
    public function index(): Response
    {
        return $this->render('library/index.html.twig', [
            'controller_name' => 'LibraryController',
        ]);
    }

    #[Route('/library/create', name: 'library_create')]
    public function createLibrary(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $library = new Library();
        $library->setTitle('Sample Book ' . rand(1, 100));
        $library->setIsbn('978-3-' . rand(1000000, 9999999));
        $library->setAuthor('Author ' . rand(1, 5));
        $library->setImagePath('uploads/sample.jpg');

        $entityManager->persist($library);
        $entityManager->flush();

        return new Response('Saved new library entry with id ' . $library->getId());
    }

    #[Route('/library/show', name: 'library_show_all')]
    public function showAllLibrary(LibraryRepository $libraryRepository): Response
    {
        $libraries = $libraryRepository->findAll();

        return $this->json($libraries);
    }

    #[Route('/library/view', name: 'library_view_all')]
    public function viewAllLibrary(
        LibraryRepository $libraryRepository
    ): Response {
        $libraries = $libraryRepository->findAll();

        $data = [
            'libraries' => $libraries
        ];

        return $this->render('library/view.html.twig', $data);
    }
}
