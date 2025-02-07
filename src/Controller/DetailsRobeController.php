<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DetailsRobeController extends AbstractController
{
    #[Route('/details/robe/{id}', name: 'app_details_robe')]
    public function index($id, ProduitsRepository $produitsRepository, EntityManagerInterface $entityManager): Response
    {
        // Récupération du produit (robe) par ID
        $robe = $produitsRepository->find($id);

        if (!$robe) {
            throw $this->createNotFoundException('Robe non trouvée.');
        }

        // Récupération des tailles, couleurs et images
        $tailles = array_map(fn($taille) => $taille->getTaille(), $robe->getTaille()->toArray());
        $couleurs = array_map(fn($couleur) => $couleur->getCouleur(), $robe->getCouleur()->toArray());
        $images = array_map(fn($image) => $image->getImage(), $robe->getImages()->toArray());

        // Vérification si l'utilisateur a acheté ce produit
        $achat = false;
        $user = $this->getUser();

        if ($user) {
            $commandes = $entityManager->getRepository(Commande::class)->findBy(['user' => $user]);

            foreach ($commandes as $commande) {
                foreach ($commande->getCommandeProduits() as $produit) {
                    if ($produit->getId() == $id) {
                        $achat = true;
                        break 2; // On sort des deux boucles dès qu'on trouve le produit
                    }
                }
            }
        }

        // Rendu de la page Twig
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
