<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FAQController extends AbstractController
{
    #[Route('/f/a/q', name: 'app_f_a_q')]
    public function index(): Response
    {
        $faqs = [
            'Commande et paiement' => [
                'Comment passer une commande sur votre site ?' => 'Vous pouvez passer une commande en parcourant nos catégories de produits, en ajoutant les articles souhaités à votre panier, et en suivant le processus de paiement sécurisé.',
                'Quels moyens de paiement acceptez-vous ?' => 'Nous acceptons les cartes de crédit (Visa, MasterCard, etc.), PayPal, et d\'autres moyens locaux selon votre pays.',
                'Mon paiement est-il sécurisé ?' => 'Oui, nous utilisons des protocoles de sécurité avancés comme SSL pour protéger vos informations bancaires.',
                'Puis-je modifier ou annuler une commande après l’avoir passée ?' => 'Vous pouvez modifier ou annuler une commande tant qu\'elle n\'a pas été expédiée. Contactez notre service client rapidement pour assistance.',
            ],
            'Livraison' => [
                'Quels sont vos délais de livraison ?' => 'Nos délais de livraison varient de 3 à 7 jours ouvrables selon votre emplacement.',
                'Proposez-vous la livraison internationale ?' => 'Oui, nous livrons dans plusieurs pays. Les frais et délais peuvent varier en fonction de la destination.',
                'Puis-je suivre ma commande en temps réel ?' => 'Oui, un lien de suivi vous sera envoyé par e-mail une fois votre commande expédiée.',
                'Que faire si ma commande est endommagée ou incomplète ?' => 'Contactez notre service client dans les 48 heures avec des photos ou des détails pour résoudre rapidement le problème.',
            ],
            'Retour et remboursement' => [
                'Quelle est votre politique de retour ?' => 'Vous pouvez retourner un produit dans un délai de 14 jours après réception, à condition qu\'il soit dans son état d\'origine.',
                'Comment demander un remboursement ?' => 'Remplissez notre formulaire de retour en ligne ou contactez le service client. Nous vous guiderons dans le processus.',
                'Combien de temps faut-il pour recevoir mon remboursement ?' => 'Les remboursements sont généralement traités dans un délai de 5 à 10 jours ouvrables après réception de votre retour.',
            ],
        ];


        return $this->render('faq/index.html.twig', [
            'controller_name' => 'FAQController',
            'bodyClass'=>null,
            'faqs' => $faqs,
        ]);
    }
}
