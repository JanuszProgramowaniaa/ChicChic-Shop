<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        $cache = new FilesystemAdapter();
        //$cache->clear();

        $cacheTime = 3600;
        $latestProducts = $cache->get('latestProducts_Cache', function (ItemInterface $item) use ($cacheTime, $productRepository): ?array {
            $item->expiresAfter($cacheTime);
        
            $latestProducts = $productRepository->findLatests(6);
    
            return $latestProducts;
        });

        $bestsellerProducts = $cache->get('bestsellerProducts_Cache', function (ItemInterface $item) use ($cacheTime, $productRepository): ?array {
            $item->expiresAfter($cacheTime);
        
            $bestsellerProducts = $productRepository->findBestsellers(6);
        
            return $bestsellerProducts;
        });

        $categories = $cache->get('categories_Cache', function (ItemInterface $item) use ($cacheTime, $categoryRepository): ?array {
            $item->expiresAfter($cacheTime);
        
            $categoryProducts = $categoryRepository->findAll();
        
            return  $categoryProducts;
        });
    
        return $this->render('index/index.html.twig', [
            'latestProducts' => $latestProducts,
            'bestsellerProducts' => $bestsellerProducts,
            'categories' => $categories
        ]);
    }
}
