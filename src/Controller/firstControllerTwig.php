<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class firstControllerTwig extends AbstractController
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

        // Determine the image URL based on the value of $number
        if ($number > 50) {
            $imageUrl = 'background.jpg';
        } else {
            $imageUrl = 'symfony.jpg';
        }

        // Add the image URL to the $data array
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
    public function card_main(): Response
    {
        return $this->render('card/card_main.html.twig');
    }


}
