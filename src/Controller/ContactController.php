<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class ContactController extends AbstractController
{
    /**
     * Displays a contact page along with an inquiry form
     * @return Response 
     */
    #[Route('/contact', name: 'app_contact_index')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
    
            // Send email
            $email = (new Email())
                ->from('your_email@example.com')
                ->to('recipient@example.com')
                ->subject('Contact Form Submission')
                ->text('Email: ' . $data['email'] . "\nMessage: " . $data['message']);
    
            $mailer->send($email);
    
            $form->clear(); 
          
            return $this->redirectToRoute('homepage');
        }

        $cache = new FilesystemAdapter();
        $cacheTime = 3600;

    

        return $this->render('contact/index.html.twig', [
            'contact_form' => $form->createView(),
            'contact_email' => 'damianwasiak49test@gmail.com'
         
        ]);
    }
}