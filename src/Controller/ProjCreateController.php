<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;
use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class ProjCreateController extends AbstractController
{
  
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

            return $this->redirectToRoute('proj_brands');
        }

        $categories = $doctrine->getRepository(Category::class)->findAll();

        return $this->render('proj/forms/create.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/proj/brand/{id}', name: 'post_show', requirements: ['id' => '\d+'])]
    public function show(int $id, ManagerRegistry $doctrine): Response
    {
        $post = $doctrine->getRepository(Post::class)->find($id);

        if (!$post) {
            throw $this->createNotFoundException('The post does not exist');
        }

        return $this->render('proj/view-proj-detail.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/proj/delete/{id}', name: 'post_delete', methods: ['POST'])]
    public function deletePost(int $id, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $post = $entityManager->getRepository(Post::class)->find($id);

        if (!$post) {
            throw $this->createNotFoundException('No post found for id ' . $id);
        }

        $entityManager->remove($post);
        $entityManager->flush();

        $this->addFlash('success', 'Post deleted successfully.');

        return $this->redirectToRoute('proj_brands');
    }

}