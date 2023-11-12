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
    #[Route('/contact', name: 'app_contact')]
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
    
            // Clear form data
            $form->clear(); // Symfony 5.3 and later
            // For Symfony versions prior to 5.3, you can reset the form
            // $form = $this->createForm(ContactType::class);
    
            // Redirect or display success message
            // For example, redirect to the homepage
            return $this->redirectToRoute('homepage');
        }

        return $this->render('contact/index.html.twig', [
            'contact_form' => $form->createView(),
            'contact_email' => 'damianwasiak49test@gmail.com'
        ]);
    }
}