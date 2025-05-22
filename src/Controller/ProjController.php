<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;
use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class ProjController extends AbstractController
{
    #[Route("/proj", name: "proj_main")]
    public function projHome(): Response
    {
        return $this->render('proj/main-page.html.twig');
    }

    #[Route("/proj/about", name: "proj_about")]
    public function projAbout(): Response
    {
        return $this->render('proj/proj-about.html.twig');
    }

    #[Route("/proj/brands", name: "proj_brands")]
    public function projBrands(): Response
    {
        return $this->render('proj/brands-page.html.twig');
    }


    #[Route('/proj/create', name: 'post_create')]
    public function createPost(Request $request, ManagerRegistry $doctrine): Response
    {
        if ($request->isMethod('POST')) {
            $title = $request->request->get('title');
            $description = $request->request->get('description');
            $categoryId = $request->request->get('category');

            $imageFile = $request->files->get('image');
            $imagePath = null;

            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('kernel.project_dir') . '/public/uploads',
                    $newFilename
                );
                $imagePath = 'uploads/' . $newFilename;
            } else {
                $imagePath = 'uploads/default.jpg';
            }

            $category = $doctrine->getRepository(Category::class)->find($categoryId);

            if (!$category) {
                $this->addFlash('error', 'Selected category not found!');
                return $this->redirectToRoute('post_create');
            }

            $post = new Post();
            $post->setTitle($title);
            $post->setDescription($description);
            $post->setImage($imagePath);
            $post->setCategory($category);

            $em = $doctrine->getManager();
            $em->persist($post);
            $em->flush();

            $this->addFlash('success', 'Post created successfully!');

            return $this->redirectToRoute('post_create');
        }

        $categories = $doctrine->getRepository(Category::class)->findAll();

        return $this->render('proj/forms/create.html.twig', [
            'categories' => $categories,
        ]);
    }

}