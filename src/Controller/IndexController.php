<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(ProductRepository $productRepository): Response
    {

        $latestProducts = $productRepository->findLatests(6);
        $bestsellerProducts = $productRepository->findBestsellers(6);

        return $this->render('index/index.html.twig', [
            'items' => $latestProducts,
            'items2' => $bestsellerProducts
        ]);
    }
}
