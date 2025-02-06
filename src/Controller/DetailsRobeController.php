<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Commande;
use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DetailsRobeController extends AbstractController
{
    #[Route('/details/robe/{id}', name: 'app_details_robe')]
    public function index($id, ProduitsRepository $produitsRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $robe = $produitsRepository->find($id);
        $taillesTab = $robe->getTaille();
        $couleursTab = $robe->getCouleur();
        $imagesTab = $robe->getImages();
        $tailles = [];
        $couleurs = [];
        $images = [];

        foreach ($taillesTab as $taille) {
            $tailles[] = $taille->getTaille();
        }

        foreach ($couleursTab as $couleur) {
            $couleurs[] = $couleur->getCouleur();
        }
        foreach ($imagesTab as $image) {
            $images[] = $image->getImage();
            $commandes = $entityManager->getRepository(Commande::class)->findAll();
            $achat = false;
            foreach ($commandes as $commande) {
                if ($this->getUser()) {
                    if ($commande->getUser()->getId() == $this->getUser()->getId()) {
                        foreach ($commande->getProduits() as $produit) {
                            if ($produit->getId() == $id) {
                                $achat = true;
                            }
                            break;
                        }
                    }
                }
            }
        }

        return $this->render('details_robe/index.html.twig', [
            'controller_name' => 'DetailsRobeController',
            'robe' => $robe,
            'bodyClass' => null,
            'tailles' => $tailles,
            'couleurs' => $couleurs,
            'images' => $images,
            'achat' => $achat
        ]);
    }
}
