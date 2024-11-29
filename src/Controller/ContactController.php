<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);

       
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            
    

            return $this->render('contact/success.html.twig', [
                'data' => $data,
            ]);
        }

        return $this->render('contact/index.html.twig', [
            'contact_form' => $form->createView(),
            'bodyClass' => 'contact-image'
        ]);
    }
}

