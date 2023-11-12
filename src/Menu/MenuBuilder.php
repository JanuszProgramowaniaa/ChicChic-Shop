<?php
namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class MenuBuilder
{
  
    private $factory;
    private $urlMatcher;
    private $requestStack;

    public function __construct(FactoryInterface $factory, UrlMatcherInterface $urlMatcher, RequestStack $requestStack)
    {
        $this->factory = $factory;
        $this->urlMatcher = $urlMatcher;
        $this->requestStack = $requestStack;
    }

    public function createMainMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        $request = $this->requestStack->getCurrentRequest();
        $parameters = $this->urlMatcher->matchRequest($request);
  
        $routeName = $parameters['_route'];
     
        if ($routeName === 'app_products_index') {
            $home = $menu->addChild('Home', ['route' => 'app_index']);
            $home->addChild('Products', ['route' => 'app_products_index']);
        } elseif ($routeName === 'app_contact_index') {
          
            $home = $menu->addChild('Home', ['route' => 'app_index']);
            $home->addChild('Contact', ['route' => 'app_contact_index']);

        }

        return $menu;
    }
}