<?php

namespace App\Controller;

use App\Repository\ProduitsRepository;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RobesController extends AbstractController
{
    #[Route('/robes', name: 'app_robes')]
    public function index(Request $request, ProduitsRepository $produitsRepository): Response
    {
        // Récupération des produits avec pagination
        $robes = $produitsRepository->findAllPaginated();

        // Définition du nombre d'éléments par page
        $robes->setMaxPerPage(12);

        // Récupération du numéro de page dans l'URL (?page=1, ?page=2, etc.)
        $robes->setCurrentPage($request->query->get('page', 1));

        return $this->render('robes/index.html.twig', [
            'controller_name' => 'RobesController',
            'robes' => $robes, // Liste paginée des robes
            'bodyClass' => 'robes_image'
        ]);
    }
}
