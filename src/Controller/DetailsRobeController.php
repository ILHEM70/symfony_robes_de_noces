<?php

namespace App\Controller;

use App\Repository\ProduitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DetailsRobeController extends AbstractController
{
    #[Route('/details/robe/{id}', name: 'app_details_robe')]
    public function index($id, ProduitsRepository $produitsRepository): Response
    {
        $robe = $produitsRepository->find($id);
        return $this->render('details_robe/index.html.twig', [
            'controller_name' => 'DetailsRobeController',
            'robe' => $robe,
            'bodyClass' => null

        ]);
    }
}
