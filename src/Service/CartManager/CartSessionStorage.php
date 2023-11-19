<?php

namespace App\Service\CartManager;

use App\Entity\ShoppingCart;
use App\Repository\ShoppingCartRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;

class CartSessionStorage
{
    /**
     * The request stack.
     *
     * @var RequestStack
     */
    private $requestStack;

    /**
     * The cart repository.
     *
     * @var ShoppingCartRepository
     */
    private $shoppingCartRepository;

     /**
     * The security.
     *
     * @var Security
     */
    private $security;

    /**
     * @var string
     */
    public const CART_KEY_NAME = 'cart_id';

    /**
     * CartSessionStorage constructor.
     */
    public function __construct(RequestStack $requestStack, ShoppingCartRepository $shoppingCartRepository, Security $security)
    {
        $this->requestStack = $requestStack;
        $this->shoppingCartRepository = $shoppingCartRepository;
        $this->security = $security;
    }

    /**
     * Gets the cart in session.
     */
    public function getCart(): ?ShoppingCart
    {
        if($this->getCartId()){
            return $this->shoppingCartRepository->findOneById($this->getCartId());
        }
        return null;
    }

    /**
     * Sets the cart in session.
     */
    public function setCart(ShoppingCart $cart): void
    {
        $this->getSession()->set(self::CART_KEY_NAME, $cart->getId());
    }


    public function removeCart(): void
    {
        $this->getSession()->remove(self::CART_KEY_NAME);
        return; 
    }

    /**
     * Returns the cart id.
     */
    private function getCartId(): ?int
    {
        return $this->getSession()->get(self::CART_KEY_NAME);
    }

    private function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();
    }
}
