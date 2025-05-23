<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;
use Doctrine\Persistence\ManagerRegistry;

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
    public function projBrands(ManagerRegistry $doctrine): Response
    {
        $posts = $doctrine->getRepository(Post::class)->findAll();

        return $this->render('proj/brands-page.html.twig', [
            'posts' => $posts,
        ]);
    }


}
