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


class MenuBuilder
{
    private $factory;
    private $router;
    private $logger;
    private $entityManager;
    private $requestStack;
    private $translator;
    private $security;
    private $locale;

    public function __construct(FactoryInterface $factory, RequestStack $requestStack, EntityManagerInterface $entityManager, LoggerInterface $logger, UrlGeneratorInterface $router, TranslatorInterface $translator, Security $security)
    {
        $this->factory = $factory;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->router = $router;
        $this->translator = $translator;
        $this->requestStack = $requestStack;
        $this->security = $security;
        $this->locale = $this->requestStack->getCurrentRequest()->getLocale();
        $this->userRoles = $this->security->getUser()->getRoles();

    }


    public function createMenuTree(): ItemInterfaceMenu{
        $cache = new FilesystemAdapter();
   
 
        $menu = $cache->get("menu_cache2", function (ItemInterface $categoryMenu){
            $categoryMenu->expiresAfter(60*60*24);
            
            $firstChildLabelName = "index";

            $categoryRepository = $this->entityManager->getRepository(Category::class);
            
            $factory = new MenuFactory();
            $menu = $factory->createItem('root');
            
            /* Domyślnie nazwa etykiety pierwszego dziecka "Gadżety reklamowe */
            $menu->addChild('index', ['uri' => '/']);

            $menu['index']->setLabel($firstChildLabelName);

            /* Dla menu znajdującego się na górze strony */
            $menu['index']->addChild('headerMenu')->setChildrenAttribute('class', 'list-unstyled m-0 d-flex justify-content-evenly gap-3')->setAttribute('class', 'd-none');
            $menu['index']['headerMenu']->addChild($this->translator->trans('Homepage'),  ['uri' => $this->router->generate('app_index')])->setAttribute('class', 'hidden-childrens');
            $menu['index']['headerMenu']->addChild($this->translator->trans('Contact'),  ['uri' => $this->router->generate('app_contact_index')]);
          
            
            return $menu;
        });

        return $menu;
    }

}