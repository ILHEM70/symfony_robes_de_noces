<?php
// src/Controller/AvisController.php
namespace App\Controller;

use App\Entity\Avis;
use App\Form\AvisType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AvisController extends AbstractController
{
    #[Route('/avis', name: 'app_avis')]
    public function index(EntityManagerInterface $entityManagerInterface): Response
    {
        $avis = $entityManagerInterface->getRepository(Avis::class)->findAll();

        return $this->render('avis/index.html.twig', [
            'avis' => $avis,
        ]);
    }

    #[Route('/avis/ajouter', name: 'ajouter_avis')]
    public function ajouter(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        // Créer un nouvel objet Avis
        $avis = new Avis();
        

        // Créer le formulaire
        $form = $this->createForm(AvisType::class, $avis);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarder l'avis dans la base de données
            $entityManagerInterface->persist($avis);
            $entityManagerInterface->flush();

            // Afficher un message de succès
            $this->addFlash('success', 'Votre avis a bien été ajouté !');

            // Rediriger vers la page des avis
            return $this->redirectToRoute('app_avis');
        }

        // Afficher le formulaire
        return $this->render('avis/ajouter.html.twig', [
            'form' => $form->createView(),
            'bodyClass' => null,
           
        ]);
    }
}
