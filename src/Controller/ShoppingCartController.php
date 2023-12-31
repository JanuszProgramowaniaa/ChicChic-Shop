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
use App\Entity\Order;
use App\Entity\OrderEntry;
use App\Entity\OrderStatus;
use App\Repository\ProductRepository;
use App\Repository\ShoppingCartEntryRepository;
use App\Repository\UserRepository;
use App\Repository\AddressRepository;
use App\Repository\OrderStatusRepository;
use App\Service\CartManager\CartManager;
use App\Form\AddressType;


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
    public function add(Request $request, CartManager $cartManager, ProductRepository $productRepository, Security $security): JsonResponse
    {
        if($request->getMethod() === 'POST') {

            $user = $security->getUser();
            if (!$user) {
                return $this->json(['error' => 'User not logged.'], JsonResponse::HTTP_UNAUTHORIZED);
            }
                                      
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
    #[Route(path: '/cart/addressDelivery', name: 'app_shopping_cart_address_delivery', methods: ['GET', 'POST'])]
    public function addressDelivery(Request $request, Security $security, AddressRepository $addressRepository, EntityManagerInterface  $entityManager, CartManager $cartManager): Response
    {
        $user = $security->getUser();

        if(!$user){
            $this->addFlash('error', 'To see your delivery address, you must be logged in to your account');
            return $this->redirectToRoute('app_shopping_cart');
        }

        $cart = $cartManager->getCurrentCart();
   
        if (count($cart->getShoppingCartEntry()) == 0) {
            $this->addFlash('error', 'The shopping cart is empty');
            return $this->redirectToRoute('app_shopping_cart');
        }

        $address = $addressRepository->findOneBy(['user' => $user]);
        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($address);
            $entityManager->flush();
            
            $this->addFlash('success', 'Save changes');
            return $this->redirectToRoute('app_shopping_cart_address_delivery');
        }

        return $this->render('cart/address_delivery.html.twig', [
            'address_form' => $form->createView(),]);

    }

    /**
     * @param Request $request
     * @param CartManager $cartManager
     * @param ProductRepository $productRepository
     * 
     * @return Response
     */
    #[Route(path: '/cart/summary', name: 'app_shopping_cart_summary', methods: ['GET'])]
    public function summary(Request $request, Security $security, AddressRepository $addressRepository, EntityManagerInterface  $entityManager, CartManager $cartManager): Response
    {
        $user = $security->getUser();

        if(!$user){
            $this->addFlash('error', 'To see the delivery address you need to deliver');
            return $this->redirectToRoute('app_shopping_cart');
        }

        $cart = $cartManager->getCurrentCart();
   
        if (count($cart->getShoppingCartEntry()) == 0) {
            $this->addFlash('error', 'The shopping cart is empty');
            return $this->redirectToRoute('app_shopping_cart');
        }

        $address = $addressRepository->findOneBy(['user' => $user]);

        if(!$address){
            $this->addFlash('error', 'You must provide a delivery address');
            return $this->redirectToRoute('app_shopping_cart_address_delivery');
        }

        return $this->render('cart/summary.html.twig',[
            'address' => $address
        ]);

    }

    /**
     * @param Request $request
     * @param CartManager $cartManager
     * @param ProductRepository $productRepository
     * 
     * @return Response
     */
    #[Route(path: '/cart/send', name: 'app_shopping_cart_send', methods: ['GET'])]
    public function send(Request $request, Security $security, AddressRepository $addressRepository,OrderStatusRepository $orderStatusRepository, EntityManagerInterface  $entityManager, CartManager $cartManager): Response
    {
        $user = $security->getUser();

        if(!$user){
            $this->addFlash('error', 'To see the delivery address you need to deliver');
            return $this->redirectToRoute('app_shopping_cart');
        }

        $cart = $cartManager->getCurrentCart();
   
        if (count($cart->getShoppingCartEntry()) == 0) {
            $this->addFlash('error', 'The shopping cart is empty');
            return $this->redirectToRoute('app_shopping_cart');
        }

        $address = $addressRepository->findOneBy(['user' => $user]);

        if(!$address){
            $this->addFlash('error', 'You must provide a delivery address');
            return $this->redirectToRoute('app_shopping_cart_address_delivery');
        }
        
        $order = new Order();
        // Order info
        $order->setProductsum($cart->getProductsum());
        $order->setDeliverysum($cart->getDeliverysum());
        $order->setNote($cart->getNote());
        $order->setDateadded(new \DateTime('now'));

        // Address
        $address = $cart->getUser()->getAddress();
        $order->setPerson($address->getFirstname().' '.$address->getLastname());
        $order->setCompany($address->getCompany());
        $order->setPhone($address->getPhone());
        $order->setAddress($address->getAddress());
        $order->setZip($address->getZip());
        
        // Status
        $order->setOrderstatus($orderStatusRepository->find(OrderStatus::NEW));

        //  Entries
        $cartEntries = $cart->getShoppingCartEntry();
        
        foreach ($cartEntries as $entry) {
            $orderEntry = new OrderEntry();

            $orderEntry->setQuantity($entry->getQuantity());
            $orderEntry->setProduct($entry->getProduct());
            $orderEntry->setPrice($entry->getPrice());
                           
            $order->addOrderEntry($orderEntry);
        }
        
        $entityManager->beginTransaction();
        
        try {
            $entityManager->persist($order);
            $entityManager->flush();
        
            // Pobierz identyfikator koszyka przed usunięciem
            $cartId = $cart->getId();
        
            $entityManager->remove($cart);
            $entityManager->flush();
        
            $entityManager->commit();
        
            return $this->render('cart/send.html.twig', ['cartId' => $cartId]);
        } catch (\Exception $e) {
            $entityManager->rollback();
    
            $this->addFlash('error', 'An error occurred, please try again later');
            return $this->redirectToRoute('app_shopping_cart');
        }

    } 

}
