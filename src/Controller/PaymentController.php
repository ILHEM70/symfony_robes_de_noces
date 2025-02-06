<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Produits;
use App\Form\PaymentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    #[Route('/payment', name: 'app_payment')]
    public function index(Request $request, SessionInterface $sessionInterface): Response
    {
        $panier = $sessionInterface->get('panier', []);
        $total = 0;

        foreach ($panier as $p) {
            $total += $p['produit']->getPrix() * $p['quantity'];
        }

        $form = $this->createForm(PaymentType::class);
        $form->handleRequest($request);

        return $this->render('payment/index.html.twig', [
            'controller_name' => 'PaymentController',
            'bodyClass' => null,
            'total' => $total,
            'items' => $panier,
            'form' => $form->createView(),
        ]);
    }

    #[Route("/payment/confirm", name: "payment_confirm", methods: ["POST"])]
    public function confirmPayment(Request $request, SessionInterface $sessionInterface, EntityManagerInterface $entityManager): Response
    {
        $paymentMethod = $request->request->get('payment_method');
        $panier = $sessionInterface->get('panier', []);

        if ($paymentMethod) {
            $adress = $sessionInterface->get('adress', []);
            $total = 0;

            // Instancier la commande
            $commande = new Commande();
            $commande->setVille($adress['ville'])
                ->setAdresse($adress['adress'])
                ->setCodePostal($adress['code_postal'])
                ->setDateCommande(new \DateTime()) // Date actuelle
                ->setEtatCommande('En attente')
                ->setReferenceCommande('CMD-' . date('Ymd-His') . '-' . strtoupper(uniqid()))
                ->setUser($this->getUser())
                ->setPays($adress['pays']);

            foreach ($panier as $p) {
                $produit = $p['produit'];

                // Vérification du stock
                if ($produit->getStock() < $p['quantity']) {
                    $this->addFlash('error', "Stock insuffisant pour le produit : " . $produit->getNom());
                    return $this->redirectToRoute('app_payment');
                }

                // Déduction du stock
                $produit->setStock($produit->getStock() - $p['quantity']);

                $total += $produit->getPrix() * $p['quantity'];
                $commande->addProduit($produit);
            }

            $commande->setTotal($total);
            $entityManager->persist($commande);
            $entityManager->flush();

            // Vider le panier
            $sessionInterface->remove('panier');
            $sessionInterface->remove('nb');
            $sessionInterface->remove('adress');

            $this->addFlash('success', 'Votre paiement a été confirmé avec succès !');

            // Redirection vers la page de confirmation
            return $this->redirectToRoute('payment_thank_you');
        }

        $this->addFlash('error', 'Erreur lors du paiement. Veuillez réessayer.');
        return $this->redirectToRoute('app_payment');
    }

    #[Route("/payment/thank-you", name: "payment_thank_you")]
    public function thankYou(): Response
    {
        return $this->render('payment/confirmation.html.twig', [
            'bodyClass' => null
        ]);
    }
}
