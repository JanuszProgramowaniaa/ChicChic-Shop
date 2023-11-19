<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AboutAsController extends AbstractController
{
    #[Route('/about/as', name: 'app_about_as')]
    public function index(): Response
    {
        return $this->render('about_as/index.html.twig', [
        ]);
    }
}
