<?php

namespace App\Controller;

use App\Entity\Avis;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AvisController extends AbstractController
{
    #[Route('/avis', name: 'app_avis')]
    public function index(EntityManagerInterface $entityManagerInterface): Response
    {
        $avis = $entityManagerInterface->getRepository(Avis::class)-> findAll();
        return $this->render('avis/index.html.twig', [
            'controller_name' => 'AvisController',
            'avis'=> $avis,
            'bodyClass' => null
        ]);
    }
}
