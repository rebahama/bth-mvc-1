<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjController extends AbstractController
{
    #[Route("/proj", name: "proj_main")]
    public function projHome(): Response
    {
        return $this->render('proj/main-page.html.twig');
    }
   


}