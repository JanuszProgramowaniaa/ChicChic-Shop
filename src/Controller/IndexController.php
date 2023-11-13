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

        $paginatedProducts = $productRepository->findAllPaginated(2, 6);
        $paginatedProducts2 = $productRepository->findBestsellers(6);

        return $this->render('index/index.html.twig', [
            'items' => $paginatedProducts,
            'items2' => $paginatedProducts2
        ]);
    }
}
