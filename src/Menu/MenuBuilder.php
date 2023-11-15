<?php
// src/Menu/MenuBuilder.php

namespace App\Menu;


// Komponenty symfony
use Symfony\Component\Yaml\Yaml;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;

// Komponenty Knp Menu
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface as ItemInterfaceMenu;
use Knp\Menu\MenuFactory;

// Encje
use App\Entity\Category;

// Cache
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

use App\Repository\CategoryRepository;


class MenuBuilder
{
    private $factory;
    private $router;
    private $logger;
    private $entityManager;
    private $requestStack;
    private $categoryRepository;
  

    public function __construct(FactoryInterface $factory, RequestStack $requestStack, EntityManagerInterface $entityManager,  UrlGeneratorInterface $router, CategoryRepository $categoryRepository)
    {
        $this->factory = $factory;
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->requestStack = $requestStack;
        $this->categoryRepository = $categoryRepository;
    
    }


    public function createMenuTree(): ItemInterfaceMenu{
        $cache = new FilesystemAdapter();
   
 
        $menu = $cache->get("menu_cache23321321223121", function (ItemInterface $categoryMenu){
            $categoryMenu->expiresAfter(60*60*24);
            
            $firstChildLabelName = "ChicChic Shop";

            $categories = $this->categoryRepository->findAll();

          
            
            $factory = new MenuFactory();
            $menu = $factory->createItem('root');
            
            $menu->addChild('index', ['uri' => '/']);

            $menu['index']->setLabel($firstChildLabelName);
            $menu['index']->addChild('Products',  ['uri' => $this->router->generate('app_products_index')]);
            
            
            $menu['index']->addChild('headerMenu')->setChildrenAttribute('class', 'list-unstyled m-0 d-flex justify-content-evenly gap-3')->setAttribute('class', 'd-none');
            $menu['index']['headerMenu']->addChild('Contact',  ['uri' => $this->router->generate('app_contact_index')]);


            foreach ($categories as $category) {
                $menu['index']->addChild($category->getName(),['uri' => $this->router->generate('app_products_category', ['categoryId' => $category->getId()])]);
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