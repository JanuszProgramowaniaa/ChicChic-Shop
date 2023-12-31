<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use App\Form\AddressType;
use App\Entity\Address;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OrderRepository;

class AccountController extends AbstractController
{   
    /**
     * Displays a page with all products, filtering by bestsellers and new products from the last month, and additionally searches by product category.
     * @param Request $request Handling requests
     * @param Security $security User authetication object
     * 
     * @return Response 
     */
    #[Route('/account', name: 'app_account_index')]
    public function index(Request $request, Security $security, EntityManagerInterface  $entityManager): Response
    {
        $user = $security->getUser();
        
        if($user->getAddress()){
            $address = $user->getAddress();
        } else {
            $address = new Address();
            $address->setUser($user);
        }
        
        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($address);
            $entityManager->flush();
            
            $this->addFlash('success', 'Save changes');
            return $this->redirectToRoute('app_account_index');
        }
      
        return $this->render('account/index.html.twig', [
            'address_form' => $form->createView(),
        ]);
    }


    /**
     * Displays all orders for login user
     * @param Request $request Handling requests
     * @param Security $security User authetication object
     * 
     * @return Response 
     */
    #[Route('/account/orders/{page}', name: 'app_account_orders')]
    public function orders(Request $request, Security $security, OrderRepository $orderRepository, int $page = 1): Response
    {
        $user = $security->getUser();
        
        if(!$user){
            $this->addFlash('error', 'You not logged');
            return $this->redirectToRoute('app_login');
        }
        
        $orders = $orderRepository->findAll();


        return $this->render('account/orders.html.twig', [
            'orders' => $orders,
        ]);
    }

   /**
     * Displays details for a specific order
     * 
     * @param Security $security User authetication object
     * @param Order $order The order entity
     * 
     * @return Response
     * 
     */
    #[Route('/account/order/details/{id}', name: 'app_account_order_details')]
    public function orderDetails(Security $security, OrderRepository $orderRepository, int $id = null): Response
    {
        $user = $security->getUser();
        
        if(!$user){
            $this->addFlash('error', 'You not logged');
            return $this->redirectToRoute('app_account_orders');
        }

        if (empty($id)) {
            $this->addFlash('error', 'Order not found');
            return $this->redirectToRoute('app_account_orders');
        }

        $order = $orderRepository->find($id);

        if (!$order) {
            $this->addFlash('error', 'Order not found');
            return $this->redirectToRoute('app_account_orders');
        }

        return $this->render('account/order.html.twig', [
            'order' => $order,
        ]);
    }


}
