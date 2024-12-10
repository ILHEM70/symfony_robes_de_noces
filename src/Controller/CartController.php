<?php



namespace App\Controller;

use App\Entity\Produits;
use App\Entity\Robe; // Entité représentant vos robes
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartController extends AbstractController
{

    #[Route("/cart", name: "app_cart")]
    public function add(SessionInterface $session, ManagerRegistry $doctrine, Request $request): Response
    {
        $requete = $request->request->get('id');
        dump($requete);
        die();

        
        $entityManager = $doctrine->getManager();
        $robe = $entityManager->getRepository(Produits::class)->find();

        if (!$robe) {
            throw $this->createNotFoundException('La robe n\'existe pas.');
        }

        // Récupérer le panier depuis la session
        $panier = $session->get('panier', []);

        // Vérifier si la robe est déjà dans le panier
        $found = false;
        foreach ($panier as &$item) {
            if ($item['id'] === $id) {
                $item['quantity'] += 1;
                $found = true;
                break;
            }
        }

        // Si la robe n'est pas encore dans le panier
        if (!$found) {
            $panier[] = [
                'id' => $robe->getId(),
                'name' => $robe->getNomDuProduit(),
                'price' => $robe->getPrix(),
                'quantity' => 1,
            ];
        }

        // Sauvegarder le panier dans la session
        $session->set('panier', $panier);

        // Rediriger vers la page du panier ou afficher un message
        return new JsonResponse();
    }

    #[Route("/panier", name: "app_panier")]
    public function show(SessionInterface $session): Response
    {
       ;
        // Récupérer le panier depuis la session
        $panier = $session->get('panier', []);

        // Calculer le total
        $total = array_reduce($panier, function ($sum, $item) {
            return $sum + $item['price'] * $item['quantity'];
        }, 0);

        // Afficher le panier
        return $this->render('cart.html.twig', [
            'panier' => $panier,
            'total' => $total,

        ]);
    }
}
