<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ContactController extends AbstractController
{
    /**
     * Displays a contact page along with an inquiry form
     * @param Request $request Handling requests
     * @param MailerInterface $mailer The service responsible for sending e-mails
     * @return Response 
     */
    #[Route('/contact', name: 'app_contact_index')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
       
            try{
                $email = (new Email())
                    ->from($data['email'])
                    ->to($_ENV['MAILER_FROM'])
                    ->subject('Contact')
                    ->text('Email: ' . $data['email'] . "\nMessage: " . $data['message']);
                
                $mailer->send($email);

            }catch(Exception $e){
                $this->addFlash('error', 'Error sending mail');
                return $this->redirectToRoute('app_contact_index');
            }
    
            $this->addFlash('success', 'Email was sent');
            return $this->redirectToRoute('app_contact_index');
        }

        return $this->render('contact/index.html.twig', [
            'contact_form' => $form->createView(),
            'contact_email' => 'damianwasiak49test@gmail.com'
         
        ]);
    }
}