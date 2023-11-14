<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CategoryRepository;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class ProductsController extends AbstractController
{
    #[Route('/products/{page}', name: 'app_products_index')]
    public function index(Request $request, ProductRepository $productRepository, CategoryRepository $categoryRepository, int $page = 1, int $itemPerPage = 6): Response
    {
        $sortBy = $request->cookies->get('selectedSort', 'name_desc');
        $filterBestseller = $request->cookies->get('filterBestseller', 0);

        $filter = [];
      
        if($filterBestseller){
            $filter['is_bestseller'] = $filterBestseller;
        }
       
        $paginatedProducts = $productRepository->findAllPaginated($page, $itemPerPage, null, null, $filter, $sortBy);

        $totalProducts = count($paginatedProducts);
        $maxPage = ceil($totalProducts / $itemPerPage);

        if ($page != 1  && $maxPage < $page ) {
            return $this->redirectToRoute('app_products_index', ['page'=>1]);
        }

        $cache = new FilesystemAdapter();
        $cacheTime = 3600;

        $categories = $cache->get('categories_Cache', function (ItemInterface $item) use ($cacheTime, $categoryRepository): ?array {
            $item->expiresAfter($cacheTime);
        
            $categoryProducts = $categoryRepository->findAll();
        
            return  $categoryProducts;
        });

        return $this->render('products/index.html.twig', [
            'items' => $paginatedProducts,
            'page' => $page,
            'itemPerPage' => $itemPerPage,
            'totalProducts' => $totalProducts,
            'maxPage' => $maxPage,
            'categories' => $categories
        ]);
    }

    #[Route('/products/category/{categoryId}/{page}', name: 'app_products_category')]
    public function productsCategory(Request $request, ProductRepository $productRepository, CategoryRepository $categoryRepository, int $categoryId, int $page = 1, int $itemPerPage = 6): Response
    {
        $sortBy = $request->cookies->get('selectedSort', 'name_desc');
        $filterBestseller = $request->cookies->get('filterBestseller', 0);

        $filter = [];
      
        if($filterBestseller){
            $filter['is_bestseller'] = $filterBestseller;
        }
       
        $paginatedProducts = $productRepository->findAllPaginated($page, $itemPerPage, $categoryId, null,  $filter, $sortBy);

        $totalProducts = count($paginatedProducts);
        $maxPage = ceil($totalProducts / $itemPerPage);

        if ($page != 1  && $maxPage < $page ) {
            return $this->redirectToRoute('app_products_category', ['page'=>1, 'categoryId'=>$categoryId]);
        }

        $cache = new FilesystemAdapter();
        $cacheTime = 3600;

        $categories = $cache->get('categories_Cache', function (ItemInterface $item) use ($cacheTime, $categoryRepository): ?array {
            $item->expiresAfter($cacheTime);
        
            $categoryProducts = $categoryRepository->findAll();
        
            return  $categoryProducts;
        });

        return $this->render('products/index.html.twig', [
            'items' => $paginatedProducts,
            'page' => $page,
            'itemPerPage' => $itemPerPage,
            'totalProducts' => $totalProducts,
            'maxPage' => $maxPage,
            'categoryId' => $categoryId,
            'categories' => $categories,
        ]);
    }



    #[Route('/products/search/{slug}/{page}', name: 'app_products_search', defaults: ['page' => 1])]
    public function search(Request $request, ProductRepository $productRepository,  CategoryRepository $categoryRepository, string $slug, int $page = 1, int $itemPerPage = 6): Response
    {
        $sortBy = $request->cookies->get('selectedSort', 'name_desc');
        $filterBestseller = $request->cookies->get('filterBestseller', 0);

        $filter = [];

        if($filterBestseller){
            $filter['is_bestseller'] = $filterBestseller;
        }

        $paginatedProducts = $productRepository->findAllPaginated($page, $itemPerPage, null, $slug, $filter, $sortBy);
        $totalProducts = count($paginatedProducts);
        
        $maxPage = ceil($totalProducts / $itemPerPage );

        if ($page != 1  && $maxPage < $page ) {
            return $this->redirectToRoute('app_products_search', ['page'=>1, 'slug'=>$slug]);
        }

        $cache = new FilesystemAdapter();
        $cacheTime = 3600;

        $categories = $cache->get('categories_Cache', function (ItemInterface $item) use ($cacheTime, $categoryRepository): ?array {
            $item->expiresAfter($cacheTime);
        
            $categoryProducts = $categoryRepository->findAll();
        
            return  $categoryProducts;
        });
        
        return $this->render('products/index.html.twig', [
            'items' => $paginatedProducts,
            'page' => $page,
            'itemPerPage' => $itemPerPage,
            'totalProducts' => $totalProducts,
            'maxPage' => $maxPage,
            'slug' => $slug,
            'categories' => $categories
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
