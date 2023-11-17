<?php

namespace App\Factory;

use App\Entity\ShoppingCart;


class ShoppingCartFactory
{
    public function create(): ShoppingCart
    {   
        $cart = new ShoppingCart();
        $cart->setDateadded(new \DateTime());
        $cart->setProductsum(0);
        $cart->setDeliverysum(0);
        return $cart;
    }

    // public function createItem(Item $item): ShoppingCartEntry
    // {
    //     $ShoppingCartEntry= new OrderItem();
    //     $ShoppingCartEntry->setItem($item);
    //     $ShoppingCartEntry->setQuantity(1);

    //     return $ShoppingCartEntry;
    // }
}
