<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ShoppingCartEntry;
use App\Repository\ProductRepository;
use App\Repository\ShoppingCartEntryRepository;
use App\Repository\UserRepository;
use App\Service\CartManager\CartManager;

use DateTime;
use Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShoppingCartController extends AbstractController
{
    /**  
     *
     * @param Security $security                                              
     * @param cartManager $cartManager                                                                           
     * @param ShoppingCartEntryRepository  $shoppingCartEntryRepository        
     *
     */
    #[Route(path: '/cart', name: 'app_shopping_cart', methods: ['GET'])]
    public function index(Security $security, CartManager $cartManager, ShoppingCartEntryRepository $shoppingCartEntryRepository): Response
    {
        return $this->render('cart/index.html.twig', [
            // 'cart' => $cart, -  global variable in twig
        ]);
    }

    /**
     *             
     * @param request $request                               
     * @param cartManager $cartManager                    
     * @param ProductRepository $productRepository                                          
     */
    #[Route(path: '/cart/add', name: 'app_shopping_cart_add', methods: ['POST'],)]
    public function add(Request $request, CartManager $cartManager, ProductRepository $productRepository): JsonResponse
    {
        if($request->getMethod() === 'POST') {
                                      
            $request = json_decode($request->getContent());

            $cart = $cartManager->getCurrentCart();

            $product = $productRepository->find($request->idproduct);

            $entry = new ShoppingCartEntry();
            $entry->setShoppingCart($cart);
            $entry->setQuantity($request->quantity);
            $entry->setProduct($product);
            
            $cart->addShoppingCartEntry($entry);
            $cart->setProductsum($cart->getProductsum()+$request->quantity*$product->getPrice());
            
            $cartManager->save($cart);
            $msg = $request->idproduct."--".$entry->getQuantity();

            return $this->json(["msg"=>$msg], $status = JsonResponse::HTTP_OK); 
        }

        throw new NotFoundHttpException('Brak strony');
    }


  
  


  

  
    



}
