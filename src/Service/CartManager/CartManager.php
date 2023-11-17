<?php

namespace App\Service\CartManager;
use App\Service\CartManager\CartSessionStorage;
use App\Entity\ShoppingCart;
use App\Factory\ShoppingCartFactory;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class CartManager
{
    /**
     * @var CartSessionStorage
     */
    private $cartSessionStorage;

    /**
     * @var ShoppingCartFactory
     */
    private $cartFactory;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;


    private $security;
    private $userRepository;

    /**
     * CartManager constructor.
     */
    public function __construct(CartSessionStorage $cartStorage, ShoppingCartFactory $cartFactory, EntityManagerInterface $entityManager, Security $security, UserRepository $userRepository)
    {
        $this->cartSessionStorage = $cartStorage;
        $this->cartFactory = $cartFactory;
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->userRepository = $userRepository;
    }

    /**
     * Gets the current cart.
     */
    public function getCurrentCart(): ShoppingCart
    {
              
            $cart = $this->cartSessionStorage->getCart();

            if (!$cart) {
        
                $user = $this->userRepository->find($this->security->getUser()->getId());
                $cart = $user->getShoppingCart();
                if($cart){
                    $this->cartSessionStorage->setCart($cart);
                    return $cart;
                }
              
                $cart = $this->cartFactory->create();

                $cart->setUser($user);
                $this->save($cart);
            }

        return $cart;
    }

    /**
     * Persists the cart in database and session.
     */
    public function save(ShoppingCart $cart): void
    {
        // Persist in database
        $this->entityManager->persist($cart);
        $this->entityManager->flush();
        // Persist in session
        $this->cartSessionStorage->setCart($cart);
    }

    public function remove(ShoppingCart $cart): void
    {
        $this->entityManager->remove($cart);
        $this->entityManager->flush();
        $this->entityManager->clear();
        
        $this->cartSessionStorage->removeCart();
    }

    
}

?>