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
    public function createLibrary(Request $request, ManagerRegistry $doctrine): Response
    {
        if ($request->isMethod('POST')) {
            $title = $request->request->get('title');
            $isbn = $request->request->get('isbn');
            $author = $request->request->get('author');


            $imageFile = $request->files->get('image');
            $imagePath = null;

            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('kernel.project_dir') . '/public/uploads',
                    $newFilename
                );
                $imagePath = 'uploads/' . $newFilename;
            }

            $library = new Library();
            $library->setTitle($title);
            $library->setIsbn($isbn);
            $library->setAuthor($author);
            $library->setImagePath($imagePath ?? 'uploads/default.jpg');

            $entityManager = $doctrine->getManager();
            $entityManager->persist($library);
            $entityManager->flush();

            return $this->redirectToRoute('library_view_all');
        }

        return $this->render('library/forms/create-book-form.html.twig');
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

    #[Route('/library/update/{id}', name: 'library_update')]
    public function updateLibrary(
        ManagerRegistry $doctrine,
        Request $request,
        int $id
    ): Response {
        $entityManager = $doctrine->getManager();
        $library = $entityManager->getRepository(Library::class)->find($id);

        if (!$library) {
            throw $this->createNotFoundException("Library item with ID $id not found.");
        }

        if ($request->isMethod('POST')) {
            $library->setTitle($request->request->get('title'));
            $library->setAuthor($request->request->get('author'));
            $library->setIsbn($request->request->get('isbn'));

            $imageFile = $request->files->get('image');

            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('kernel.project_dir') . '/public/uploads',
                    $newFilename
                );
                $imagePath = 'uploads/' . $newFilename;
                $library->setImagePath($imagePath);
            }

            $entityManager->flush();

            return $this->redirectToRoute('library_view_all');
        }

        return $this->render('library/forms/update-book-form.html.twig', [
            'library' => $library
        ]);
    }

}
