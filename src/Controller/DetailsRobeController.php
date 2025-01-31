<?php

namespace App\Controller;

use App\Entity\Avis;
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
        }

        // Créer un nouvel avis
        $avis = new Avis();
        $form = $this->createFormBuilder($avis)
            ->add('nom')
            ->add('commentaire')
            ->add('note')
            ->add('produits') // Lier l'avis au produit
            ->add('submit', \Symfony\Component\Form\Extension\Core\Type\SubmitType::class, [
                'label' => 'Ajouter un avis'
            ])
            ->getForm();

        // Traiter le formulaire de l'avis
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Assigner le produit au nouvel avis
            $avis->setProduits($robe);
            $avis->setUser($this->getUser()); // L'utilisateur connecté (si connecté)

            // Sauvegarder l'avis en base de données
            $entityManager->persist($avis);
            $entityManager->flush();

            // Message flash de succès
            $this->addFlash('success', 'Votre avis a été ajouté avec succès!');
        }

        return $this->render('details_robe/index.html.twig', [
            'controller_name' => 'DetailsRobeController',
            'robe' => $robe,
            'bodyClass' => null,
            'tailles' => $tailles,
            'couleurs' => $couleurs,
            'images' => $images,
            'form' => $form->createView(), // Ajouter le formulaire
        ]);
    }
}
