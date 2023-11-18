<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Yaml;


class ProductsController extends AbstractController
{
    /**
     * Displays a page with all products along with filtering by bestsellers and new products from the last month.
     * @param Request $request Handling requests
     * @param ProductRepository $productRepository Product repository
     * @param int $page = 1 Subpage number. Default first page.
     * @param int $itemPerPage = 6 Number of products on the page.
     * @return Response 
     */
    #[Route('/products/{page}', name: 'app_products_index')]
    public function index(Request $request, ProductRepository $productRepository, int $page = 1, int $itemPerPage = 6): Response
    {
        $filePath = '../config/siteconfig/config.yaml';
        if (file_exists($filePath)) {
            $itemPerPage = Yaml::parseFile($filePath)['config']['itemPerPage'];
        } 

        $filterAndSort = $this->filterAndSort($request);
   
        $paginatedProducts = $productRepository->findAllPaginated($page, $itemPerPage, null, null, $filterAndSort['filter'], $filterAndSort['sortBy']);

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

    /**
     * Displays a page with all products, filtering by bestsellers and new products from the last month, and additionally searches by product category.
     * @param Request $request Handling requests
     * @param ProductRepository $productRepository Product repository
     * @param CategoryRepository $categoryRepository Category repository
     * @param int $categoryId The category number by which we are looking for products.
     * @param int $page = 1 Subpage number. Default first page.
     * @param int $itemPerPage = 6 Number of products on the page. Default six products.
     * @return Response 
     */
    #[Route('/products/category/{categoryId}/{page}', name: 'app_products_category')]
    public function productsCategory(Request $request, ProductRepository $productRepository, CategoryRepository $categoryRepository, int $categoryId = 1, int $page = 1, int $itemPerPage = 6): Response
    {
        $filePath = '../config/siteconfig/config.yaml';
        if (file_exists($filePath)) {
            $itemPerPage = Yaml::parseFile($filePath)['config']['itemPerPage'];
        } 

        $filterAndSort = $this->filterAndSort($request);
       
        $paginatedProducts = $productRepository->findAllPaginated($page, $itemPerPage, $categoryId, null,  $filterAndSort['filter'], $filterAndSort['sortBy']);

        $totalProducts = count($paginatedProducts);
        $maxPage = ceil($totalProducts / $itemPerPage);

        if ($page != 1  && $maxPage < $page ) {
            return $this->redirectToRoute('app_products_category', ['page'=>1, 'categoryId'=>$categoryId]);
        }

        return $this->render('products/index.html.twig', [
            'items' => $paginatedProducts,
            'page' => $page,
            'itemPerPage' => $itemPerPage,
            'totalProducts' => $totalProducts,
            'maxPage' => $maxPage,
            'categoryId' => $categoryId
        ]);
    }

    /**
     * Displays a page with all products, filtering by bestsellers and new products from the last month, and also searches for products by phrase. Searches by product name and symbol.
     * @param Request $request Handling requests
     * @param ProductRepository $productRepository Product repository
     * @param string $slug The phrase by which we search for products is currently searched by product name and symbol.
     * @param int $page = 1 Subpage number. Default first page.
     * @param int $itemPerPage = 6 Number of products on the page. Default six products.
     * @return Response 
     */
    #[Route('/products/search/{slug}/{page}', name: 'app_products_search', defaults: ['page' => 1])]
    public function search(Request $request, ProductRepository $productRepository, string $slug, int $page = 1, int $itemPerPage = 6): Response
    {
        $filePath = '../config/siteconfig/config.yaml';
        if (file_exists($filePath)) {
            $itemPerPage = Yaml::parseFile($filePath)['config']['itemPerPage'];
        } 

        $filterAndSort = $this->filterAndSort($request);

        $paginatedProducts = $productRepository->findAllPaginated($page, $itemPerPage, null, $slug, $filterAndSort['filter'], $filterAndSort['sortBy']);
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

    /**
     * Displays a view about the product we are interested in.
     * @param int $productId = null Product ID whose information we want to see
     * @param ProductRepository $productRepository Product repository
     * @param CategoryRepository $categoryRepository Category repository
     * @return Response 
     */
    #[Route('/products/display/{productId}', name: 'app_products_display')]
    public function display(ProductRepository $productRepository, CategoryRepository $categoryRepository, int $productId = null): Response
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

        $categoryId = $product->getcategory()->getId();
        $similaryProduct = $productRepository->findBySimilaryProduct($categoryId, 6);

        return $this->render('products/display.html.twig', [
            'product' => $product,
            'similaryProduct' => $similaryProduct
        ]);
    }
    /**
     * A function that creates an array of data for filtering and sorting products
     * @param request $request = The request based on which we generate filters and sort products
     * @return Array Table with filtering and sorting products
     */
   private function filterAndSort(request $request): Array{

        $filterBestseller = $request->cookies->get('filterBestseller', 0);
        $filterLatest = $request->cookies->get('filterLatest', 0);
        $filterMinPrice = $request->cookies->get('filterMinPrice', 0);
        $filterMaxPrice = $request->cookies->get('filterMaxPrice', 0);
        $sortBy = $request->cookies->get('selectedSort', 'name_desc');

        $filter = [];
       
        if($filterBestseller){
            $filter['is_bestseller'] = $filterBestseller;
        }
        if($filterLatest){
            $filter['latest'] = $filterLatest;
        }
        if($filterMinPrice){
            $filter['minPrice'] = $filterMinPrice;
        }
        if($filterMaxPrice){
            $filter['maxPrice'] = $filterMaxPrice;
        }

        return ['filter' => $filter, 'sortBy' => $sortBy];
   }

}
