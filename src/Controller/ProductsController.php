<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;


class ProductsController extends AbstractController
{
    #[Route('/products/{page}', name: 'app_products_index')]
    public function index(Request $request, ProductRepository $productRepository, int $page = 1, int $itemPerPage = 6): Response
    {
        $sortBy = $request->cookies->get('selectedSort', 'name_desc');
        $filterBestseller = $request->cookies->get('filterBestseller', 0);

        $filter = [];
      
        if($filterBestseller){
            $filter['is_bestseller'] = $filterBestseller;
        }
       
        $paginatedProducts = $productRepository->findAllPaginated($page, $itemPerPage, null, $filter, $sortBy);

        $totalProducts = count($paginatedProducts);
        $maxPage = ceil($totalProducts / $itemPerPage);

        if ($page != 1  && $maxPage < $page ) {
            return $this->redirectToRoute('app_products_index', ['page'=>1]);
        }

        return $this->render('products/index.html.twig', [
            'items' => $paginatedProducts,
            'page' => $page,
            'itemPerPage' => $itemPerPage,
            'totalProducts' => $totalProducts,
            'maxPage' => $maxPage
        ]);
    }

    #[Route('/products/search/{slug}/{page}', name: 'app_products_search', defaults: ['page' => 1])]
    public function search(Request $request, ProductRepository $productRepository, string $slug, int $page = 1, int $itemPerPage = 6): Response
    {
        $sortBy = $request->cookies->get('selectedSort', 'name_desc');
        $filterBestseller = $request->cookies->get('filterBestseller', 0);

        $filter = [];

        if($filterBestseller){
            $filter['is_bestseller'] = $filterBestseller;
        }

        $paginatedProducts = $productRepository->findAllPaginated($page, $itemPerPage, $slug, $filter, $sortBy);
        $totalProducts = count($paginatedProducts);
        
        $maxPage = ceil($totalProducts / $itemPerPage );

        if ($page != 1  && $maxPage < $page ) {
            return $this->redirectToRoute('app_products_search', ['page'=>1, 'slug'=>$slug]);
        }
        
        return $this->render('products/index.html.twig', [
            'items' => $paginatedProducts,
            'page' => $page,
            'itemPerPage' => $itemPerPage,
            'totalProducts' => $totalProducts,
            'maxPage' => $maxPage,
            'slug' => $slug
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
