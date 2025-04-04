<?php

namespace App\Controller;

use DateTime;
use App\Entity\Commande;
use App\Entity\Produits;
use App\Form\PaymentType;
use App\Entity\CommandeProduit;
use App\Entity\Couleur;
use App\Entity\Images;
use App\Entity\Taille;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaymentController extends AbstractController
{
    #[Route('/payment', name: 'app_payment')]
    public function index(Request $request, SessionInterface $sessionInterface, EntityManagerInterface $entityManager): Response
    {
        $panier = $sessionInterface->get('panier', []);
        // On porte la portée de total à global, nous pouvons donc calculer le total pour la vue et récupérer ce même total une fois le formulaire validé pour notre entité
        global $total;
        $total = 0;

        foreach ($panier as $p) {
            $total += $p['produit']->getPrix() * $p['quantity'];
        }

        $form = $this->createForm(PaymentType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adress = $sessionInterface->get('adress', []);
            // dd($adress);
            $date = new DateTime();
            $format = $date->format('d-m-Y');
            // Instancier la commande
            $commande = new Commande();
            $commande->setUser($this->getUser())->setDateCommande($date)->setReferenceCommande(uniqid('_') . $format)->setEtatCommande()->setAdresse($adress['adress'])->setCodePostal($adress['code_postal'])->setVille($adress['ville'])->setPays($adress['pays'])->setTotal($total);

            // Persist l'objet commande 
            $entityManager->persist($commande);
            foreach ($panier as $p) {
                $produit = $entityManager->getRepository(Produits::class)->find($p['produit']->getId());
                $couleur = $entityManager->getRepository(Couleur::class)->findOneBy(['couleur' => $p['couleur']]);
                $taille = $entityManager->getRepository(Taille::class)->findOneBy(['taille' => $p['taille']]);
                $image = $entityManager->getRepository(Images::class)->findOneBy(['image' => $p['image']]);
                
                // Instancier commandeProduit
                $commandeProduit = new CommandeProduit;
                $commandeProduit->setProduit($produit);
                $commandeProduit->setCouleur($couleur);
                $commandeProduit->setTaille($taille);
                $commandeProduit->setImage($image);
                $commandeProduit->setQuantite($p['quantity']);
                $commandeProduit->setCommande($commande);
                
                $entityManager->persist($commandeProduit);
            }

            $entityManager->flush();

            // Vider le panier
            $sessionInterface->remove('panier');
            $sessionInterface->remove('nb');
            $sessionInterface->remove('adress');

            $this->addFlash('success', 'Votre paiement a été confirmé avec succès !');

            // Redirection vers la page de confirmation
            return $this->redirectToRoute('payment_confirmation');
        }

        return $this->render('payment/index.html.twig', [
            'controller_name' => 'PaymentController',
            'bodyClass' => null,
            'total' => $total,
            'items' => $panier,
            'form' => $form->createView(),
        ]);
    }


    #[Route("/payment/confirmation", name: "payment_confirmation")]
    public function thankYou(): Response
    {
        return $this->render('payment/confirmation.html.twig', [
            'bodyClass' => null
        ]);
    }
}
