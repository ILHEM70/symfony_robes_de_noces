<?php

namespace App\Controller;

use App\Form\AdressType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class AdressController extends AbstractController
{
    #[Route('/adress', name: 'app_adress')]
    public function index(Request $request, SessionInterface $sessionInterface): Response
    {
        $form = $this->createForm(AdressType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $data = $form->getData();
            $sessionInterface->set('adress', $data);
            return $this->redirectToRoute('app_payment');
            
        }
        return $this->render('adress/index.html.twig', [
            'controller_name' => 'AdressController',
            'bodyClass'=> null,
            'form'=> $form->createView(),
        ]);
    }
}
