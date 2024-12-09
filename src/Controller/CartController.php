<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Repository\ProduitsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartController extends AbstractController
{
    #[Route("/cart", name: "app_cart")]
    public function add(SessionInterface $session, ProduitsRepository $produitsRepository, Request $request): JsonResponse
    {
        // Récupérer l'ID du produit depuis la requête
        $id = json_decode($request->getContent());
        if (!$id) {
            return new JsonResponse(['error' => 'ID de produit manquant.'], Response::HTTP_BAD_REQUEST);
        }

        // Charger le produit depuis la base de données
        $robe = $produitsRepository->find($id);

        if (!$robe) {
            return new JsonResponse(['error' => 'Produit introuvable.'], Response::HTTP_NOT_FOUND);
        }

        // Récupérer ou initialiser le panier depuis la session
        $panier = $session->get('panier', []);

        // Vérifier si le produit est déjà dans le panier
        $found = false;
        foreach ($panier as &$item) {
            if ($item['id'] === $id) {
                $item['quantity'] += 1;
                $found = true;
                break;
            }
        }

        // Ajouter le produit au panier si absent
        if (!$found) {
            $panier[] = [
                'id' => $robe->getId(),
                'name' => $robe->getNomDuProduit(),
                'price' => $robe->getPrix(),
                'quantity' => 1,
            ];
        }

        // Mettre à jour la session avec le panier
        $session->set('panier', $panier);

        // Retourner une réponse JSON avec l'état du panier
        return new JsonResponse(['message' => 'Produit ajouté au panier.', 'panier' => $panier], Response::HTTP_OK);
    }

    #[Route("/panier", name: "app_panier")]
    public function show(SessionInterface $session): Response
    {
        // Récupérer le panier depuis la session
        $panier = $session->get('panier', []);

        // Calculer le total
        $total = array_reduce($panier, function ($sum, $item) {
            $price = $item['price'] ?? 0;
            $quantity = $item['quantity'] ?? 0;
            return $sum + $price * $quantity;
        }, 0);

        // Rendre la vue du panier
        return $this->render('cart/index.html.twig', [
            'panier' => $panier,
            'total' => $total,
            'bodyClass' => null
        ]);
    }
    
    #[Route("/panier", name: "cart_remove")]
public function removeFromCart(Request $request, Session  $session){
        $data = json_decode($request->getContent(), true);
        $session->remove('nb') !== 0 ? $session->set('nb', $data): 0;
        return new JsonResponse(['nobreArticles' => $data]);
}
}

