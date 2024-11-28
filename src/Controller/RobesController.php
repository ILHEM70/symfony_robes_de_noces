<?php

namespace App\Controller;

use App\Repository\ProduitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RobesController extends AbstractController
{
    #[Route('/robes', name: 'app_robes')]
    public function index(ProduitsRepository $produitsRepository): Response
    {
        $produits = $produitsRepository->findAll();
        return $this->render('robes/index.html.twig', [
            'controller_name' => 'RobesController',
            'robes' => $produits
        ]);
    }
}
