<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FirstControllerTwig extends AbstractController
{
    #[Route("/", name: "home")]
    public function home(): Response
    {
        return $this->render('home.html.twig');
    }
    #[Route("/about", name: "about")]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }
    #[Route("/report", name: "report")]
    public function report(): Response
    {
        return $this->render('report.html.twig');
    }
    #[Route("/lucky", name: "lucky")]
    public function lucky(): Response
    {
        $number = random_int(0, 100);

        $imageUrl = $number > 50 ? 'background.jpg' : 'symfony.jpg';

        $data = [
            'number' => $number,
            'imageUrl' => $imageUrl
        ];

        return $this->render('lucky_number.html.twig', $data);
    }

    #[Route("/api", name: "api")]
    public function jsonIndex(): Response
    {
        return $this->render('api.html.twig');
    }

    #[Route("/card", name: "card_main")]
    public function cardMain(): Response
    {
        return $this->render('card/card_main.html.twig');
    }

    #[Route("/metrics", name: "metrics_page")]
    public function metricsPage(): Response
    {
        return $this->render('metrics.html.twig');
    }


}
