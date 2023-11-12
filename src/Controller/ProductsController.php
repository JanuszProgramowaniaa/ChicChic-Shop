<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;

class ProductsController extends AbstractController
{
    #[Route('/products', name: 'app_products_index')]
    public function index(ProductRepository $productRepository): Response
    {

        $products = $productRepository->findAll();
        
        return $this->render('products/index.html.twig', [
            'items' => $products,
        ]);
    }

    #[Route('/products/display/{productId}', name: 'app_products_display')]
    public function display(ProductRepository $productRepository, int $productId = null): Response
    {   
        if (!$productId) {
            $this->addFlash('error', 'Product not exist !');
            return $this->redirectToRoute('app_products_index');
        }

        $product = $productRepository->find($productId);

        if (!$product) {
            $this->addFlash('error', 'Product not exist !');
            return $this->redirectToRoute('app_products_index');
        }
    
        return $this->render('products/display.html.twig', [
            'product' => $product,
        ]);
    }

}
