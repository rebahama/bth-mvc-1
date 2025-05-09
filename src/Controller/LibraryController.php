<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
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
        $library->setImagePath('img/symfony.jpg'); // Set image path here

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

    #[Route('/library/view/{id}', name: 'library_view_one')]
    public function viewOneLibrary(
        int $id,
        LibraryRepository $libraryRepository
    ): Response {
        $library = $libraryRepository->findLibrary($id);

        if (!$library) {
            throw $this->createNotFoundException("Book with ID $id not found.");
        }

        return $this->render('library/detail-view.html.twig', [
            'library' => $library
        ]);
    }

    #[Route('/library/delete/{id}', name: 'library_delete_by_id', methods: ['POST'])]
    public function deleteLibraryById(
        Request $request,
        LibraryRepository $libraryRepository,
        ManagerRegistry $doctrine,
        int $id
    ): Response {
        $entityManager = $doctrine->getManager();
        $library = $entityManager->getRepository(Library::class)->find($id);
    
        if (!$library) {
            throw $this->createNotFoundException('No library found for id '.$id);
        }
    
        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete' . $library->getId(), $submittedToken)) {
            throw $this->createAccessDeniedException('Invalid CSRF token.');
        }
    
        $entityManager->remove($library);
        $entityManager->flush();
    
        return $this->redirectToRoute('library_view_all');
    }
}
