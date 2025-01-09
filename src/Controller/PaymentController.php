<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class PaymentController extends AbstractController
{
    #[Route('/payment', name: 'app_payment')]
    public function index(SessionInterface $sessionInterface): Response
    {
        $panier = $sessionInterface->get('panier', []);
        // dd($panier);
        $total = 0;

        foreach ($panier as $p) {
            $total += $p['produit']->getPrix() * $p['quantity'];
        }

        return $this->render('payment/index.html.twig', [
            'controller_name' => 'PaymentController',
            'bodyClass' => null,
            'total' => $total,
            'items' => $panier
        ]);
    }
    #[Route("/payment/confirm", name: "payment_confirm", methods: "POST")]

    public function confirmPayment(Request $request, SessionInterface $sessionInterface)
    {
        // On récupère les données envoyées par le formulaire (par exemple, la méthode de paiement)
        $paymentMethod = $request->request->get('payment_method');

        // Simulons une logique de traitement du paiement (ici, une confirmation simple)
        if ($paymentMethod) {
            // Logique de traitement du paiement, par exemple avec une API de paiement (Stripe, etc.)
            // Ici, nous simulons un succès de paiement.
            $this->addFlash('success', 'Votre paiement a été confirmé avec succès !');
            $sessionInterface->remove('panier');
            $sessionInterface->remove('nb');
        } else {
            // Si aucune méthode de paiement n'est choisie, nous affichons un message d'erreur
            $this->addFlash('error', 'Erreur lors de la confirmation du paiement. Veuillez réessayer.');
        }

        // Rediriger l'utilisateur vers une autre page, comme une page de confirmation
        return $this->redirectToRoute('payment_thank_you');
    }

    #[Route("/payment/confirm", name: "payment_thank_you")]

    public function thankYou(){
        return $this->render('payment/confirmation.html.twig',[
            'bodyClass'=>null
        ]);
    }
}
