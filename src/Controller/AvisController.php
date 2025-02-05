<?php
// src/Controller/AvisController.php
namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Commande;
use App\Entity\Produits;
use App\Form\AvisType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AvisController extends AbstractController
{
    #[Route('/avis/{idProduit}', name: 'app_avis')]
    public function index(EntityManagerInterface $entityManagerInterface, int $idProduit): Response
    {
        $avisTab = $entityManagerInterface->getRepository(Avis::class)->findAll();
        $avis = [];
        foreach ($avisTab as $a) {
            if ($idProduit == $a->getProduits()->getId()) {
                $avis[] = $a;
            }
        }

        return $this->render('avis/index.html.twig', [
            'avis' => $avis,
        ]);
    }

    #[Route('/avis/ajouter/{idProduit}', name: 'ajouter_avis')]
    public function ajouter(Request $request, EntityManagerInterface $entityManagerInterface, int $idProduit): Response
    {
        // Créer un nouvel objet Avis
        $avis = new Avis();
        $produit = $entityManagerInterface->getRepository(Produits::class)->find($idProduit);

        // Créer le formulaire
        $form = $this->createForm(AvisType::class, $avis);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avis->setProduits($produit);
            $avis->setUser($this->getUser());
            // Sauvegarder l'avis dans la base de données
            $entityManagerInterface->persist($avis);
            $entityManagerInterface->flush();

            // Afficher un message de succès
            $this->addFlash('success', 'Votre avis a bien été ajouté !');

            // Rediriger vers la page des avis
            return $this->redirectToRoute('app_avis',['idProduit' => $idProduit]);
        }

        // Afficher le formulaire
        return $this->render('avis/ajouter.html.twig', [
            'form' => $form->createView(),
            'bodyClass' => null,

        ]);
    }
}
