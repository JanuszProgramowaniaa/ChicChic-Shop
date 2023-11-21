<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\Yaml\Yaml;

class IndexController extends AbstractController
{
    /**
     * Displays the home page with sliders with bestsellers and new products from the last month
     * @param ProductRepository $productRepository Product repository
     * @param CategoryRepository $categoryRepository Category repository
     * @return Response 
     */
    #[Route('/', name: 'app_index')]
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
   
        $cacheTime = 3600;
        $filePathConfig = '../config/siteconfig/config.yaml';
        if (file_exists($filePathConfig)) {
            $cacheTime = Yaml::parseFile($filePathConfig)['config']['cacheTime'];
        }
        
        $googleOpinions = [];
        $filePathOpinion = '../config/siteconfig/google-opinion.yaml';
        if (file_exists($filePathOpinion)) {
            $googleOpinions = Yaml::parseFile($filePathOpinion)['opinions'];
        } 
     

        $cache = new FilesystemAdapter();

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

        return $this->render('index/index.html.twig', [
            'latestProducts' => $latestProducts,
            'bestsellerProducts' => $bestsellerProducts,
            'googleOpinions' => $googleOpinions
          
        ]);
    }
}
