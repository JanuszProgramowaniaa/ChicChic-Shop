<?php

namespace App\Menu;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface as ItemInterfaceMenu;
use Knp\Menu\MenuFactory;

use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

use App\Entity\Category;
use App\Repository\CategoryRepository;

use Symfony\Component\Yaml\Yaml;

class MenuBuilder
{
    private $factory;
    private $router;
    private $entityManager;
    private $requestStack;
    private $categoryRepository;
  
    /**
     * Make a structure menu three 
     */
    public function __construct(FactoryInterface $factory, RequestStack $requestStack, EntityManagerInterface $entityManager, UrlGeneratorInterface $router, CategoryRepository $categoryRepository)
    {
        $this->factory = $factory;
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->requestStack = $requestStack;
        $this->categoryRepository = $categoryRepository;
    
    }


    public function createMenuTree(): ItemInterfaceMenu{
        $cache = new FilesystemAdapter();
   
 
        $menu = $cache->get("menu_cache", function (ItemInterface $categoryMenu){
            $categoryMenu->expiresAfter(60*60*24);
            
            $firstChildLabelName = "ChicChic Shop";

            $cacheTime = 3600;
            $filePath = '../config/siteconfig/config.yaml';
            if (file_exists($filePath)) {
                $cacheTime = Yaml::parseFile($filePath)['config']['cacheTime'];
            } 

            $cache = new FilesystemAdapter();

            $latestProducts = $cache->get('latestProducts_Cache', function (ItemInterface $item) use ($cacheTime, $categorytRepository): ?array {
                $item->expiresAfter($cacheTime);
            
                $categories = $this->categoryRepository->findAll();
        
                return $categories;
            });
         

          
            
            $factory = new MenuFactory();
            $menu = $factory->createItem('root');
            
            $menu->addChild('index', ['uri' => '/']);

            $menu['index']->setLabel($firstChildLabelName);
            $menu['index']->addChild('Products',  ['uri' => $this->router->generate('app_products_index')]);
            
            
            $menu['index']->addChild('headerMenu')->setChildrenAttribute('class', 'list-unstyled m-0 d-flex justify-content-evenly gap-3')->setAttribute('class', 'd-none');
            $menu['index']['headerMenu']->addChild('Contact',  ['uri' => $this->router->generate('app_contact_index')]);

            $menu['index']->addChild('category')->setAttribute('class', 'd-none');
            foreach ($categories as $category) {
                $menu['index']['category']->addChild($category->getName(),['uri' => $this->router->generate('app_products_category', ['categoryId' => $category->getId()])]);
            }
          
            return $menu;
        });

        $request = $this->requestStack->getCurrentRequest();
        $slug = $request->attributes->get('slug');
     
        if(!empty($slug)){
             $menu['index']->addChild('Search',  ['uri' => $this->router->generate('app_products_search', ['slug' => $slug])]);
        }

        return $menu;
    }

}