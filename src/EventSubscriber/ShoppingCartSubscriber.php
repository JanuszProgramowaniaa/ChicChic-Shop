<?php

namespace App\EventSubscriber;

use App\Service\CartManager\CartManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class ShoppingCartSubscriber implements EventSubscriberInterface
{

    private Environment $twig;
    private CartManager $cartManager;
    private Security $security;

    public function __construct(Environment $twig, Security $security, CartManager $cartManager)
    {
        $this->twig = $twig;
        $this->cartManager = $cartManager;
        $this->security = $security;
    }   

    public function onControllerEvent(ControllerEvent $event)
    {
        $request = $event->getRequest();
        
        $cart = $this->cartManager->getCurrentCart();
        $this->twig->addGlobal('cart', $cart);
        
    }

    public static function getSubscribedEvents()
    {
        return [
            ControllerEvent::class => 'onControllerEvent',
        ];
    }
}
