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
     * @return Response
     */
    #[Route(path: '/cart', name: 'app_shopping_cart', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('cart/index.html.twig', [
        ]);
    }

    /**
     *             
     * @param request $request                               
     * @param cartManager $cartManager                    
     * @param ProductRepository $productRepository    
     * 
     * @return JsonResponse                                      
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
            $entry->setPrice($product->getPrice() * $request->quantity );

            $cart->addShoppingCartEntry($entry);
            $cart->setProductsum($cart->getProductsum()+$request->quantity*$product->getPrice());
            
            $cartManager->save($cart);
            $msg = $request->idproduct."--".$entry->getQuantity();

            return $this->json(["msg"=>$msg], $status = JsonResponse::HTTP_OK); 
        }

        throw new NotFoundHttpException('Brak strony');
    }

    /**
     * Edit quantity product in shoppingcart
     *
     * @param request $request                               
     * @param cartManager $cartManager                      
     * @param ProductRepository $itemRepository    
     * 
     * @return JsonResponse               
     */
    #[Route(path: '/cart/edit', name: 'app_shopping_cart_edit', methods: ['POST'])]
    public function edit(Request $request, CartManager $cartManager, ProductRepository $productRepository): JsonResponse
    {
        if($request->getMethod() === 'POST') {

            $request = json_decode($request->getContent());
            $idproduct = $request->idproduct;
            $quantity = $request->quantity;
      
            $cart = $cartManager->getCurrentCart();

            $product = $productRepository->find($idproduct);

            if(($sce = $cart->getFilteredShoppingCartEntry("product", $product))!==null){
   
                $entry = $sce->current();
                $entry->setQuantity($quantity);
        
                $sum = 0;
                foreach($cart->getShoppingCartEntry() as $scentry){
                    $sum += $scentry->getEntrySum();
                }

                $entry->setQuantity($quantity);
                $entry->setPrice($quantity * $product->getPrice());
                $cart->setProductsum($sum);

                $cartManager->save($cart);

            }else{
                return $this->json(['There is no such product in the cart'], $status = 200);
            }

            return $this->json(['info'=>'The quantity of products has been changed', 'price'=> floatval($entry->getPrice()), 'productSum'=> $cart->getProductsum() ], $status = 200);
        }

        throw new NotFoundHttpException($translator->trans('Brak strony'));
    }


    /**
     * @param Request $request
     * @param CartManager $cartManager
     * @param ProductRepository $productRepository
     * 
     * @return JsonResponse
     */
    #[Route(path: '/cart/remove', name: 'app_shopping_cart_remove', methods: ['POST'])]
    public function remove(Request $request, CartManager $cartManager, ProductRepository $productRepository): JsonResponse
    {
        if ($request->getMethod() === 'POST') {

            $requestData = json_decode($request->getContent());

            $cart = $cartManager->getCurrentCart();
            $productId = $requestData->idproduct;
            $product = $productRepository->find($productId);
            
            $entry = $cart->getFilteredShoppingCartEntry('product', $product)->first();

            if ($entry) {
             
                $cart->removeShoppingCartEntry($entry);
                $cart->setProductsum($cart->getProductsum() - $entry->getQuantity() * $product->getPrice());
                $cartManager->save($cart);

                $msg = "Product with ID {$productId} removed from the cart.";

                return $this->json(["msg" => $msg], JsonResponse::HTTP_OK);
            } else {
                $msg = "Product with ID {$productId} not found in the cart.";
                return $this->json(["msg" => $msg], JsonResponse::HTTP_NOT_FOUND);
            }
        }

        throw new NotFoundHttpException('Brak strony');
    }


    /**
     * @param Request $request
     * @param CartManager $cartManager
     * @param ProductRepository $productRepository
     * 
     * @return Response
     */
    #[Route(path: '/cart/addressDelivery', name: 'app_shopping_cart_address_delivery', methods: ['GET'])]
    public function addressDelivery(Request $request, Security $security): Response
    {

        $user = $security->getUser();

        if(!$user){
            $this->addFlash('error', 'To see the delivery address you need to deliver');
            return $this->redirectToRoute('app_shopping_cart');
        }


        return $this->render('cart/address_delivery.html.twig', []);

    }

}
