<?php

namespace App\Controller;


use App\Repository\ProduitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartController extends AbstractController
{
    #[Route("/cart", name: "app_cart")]
    public function add(SessionInterface $session, ProduitsRepository $produitsRepository, Request $request): JsonResponse
    {

        if (!$this->getUser()) {
            return new JsonResponse(['error' => 'Merci de vous connecter pour ajouter un produit au panier !']);
        }
        // Récupérer l'ID du produit depuis la requête
        $data = json_decode($request->getContent(), true);
        $id = $data['id'];
        $couleur = $data['couleur'];
        $taille = $data['taille'];
        $image = $data['image'];

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
            if ($item['produit']->getId() === $id && $item['couleur'] === $couleur && $item['taille'] === $taille) {
                $item['quantity'] += 1;
                $found = true;
                break;
            }
        }

        // Ajouter le produit au panier si absent
        if (!$found) {
            $panier[] = [
                'produit' => $robe,
                'quantity' => 1,
                'couleur' => $couleur,
                'taille' => $taille,
                'image' => $image
            ];
        }
        $count = count($panier);

        $session->set('nb', $count);
        // Mettre à jour la session avec le panier
        $session->set('panier', $panier);

        // Retourner une réponse JSON avec l'état du panier
        return new JsonResponse(['message' => 'Produit ajouté au panier.', 'nb' => $count], Response::HTTP_OK);
    }

    #[Route("/panier", name: "app_panier")]
    public function show(SessionInterface $session): Response
    {
        // Récupérer le panier depuis la session
        $panier = $session->get('panier', []);
        // dd($panier);
        // Calculer le total
        $total = array_reduce($panier, function ($sum, $item) {
            $price = $item['produit']->getPrix() ?? 0;
            $quantity = $item['quantity'] ?? 0;
            return $sum + $price * $quantity;
        }, 0);

        // Rendre la vue du panier
        return $this->render('cart/index.html.twig', [
            'panier' => $panier,
            'total' => $total,
            'bodyClass' => null,
        ]);
    }

    #[Route("/panier/supp", name: "cart_remove")]

    public function removeItem(Request $request, SessionInterface $session)
    {

        $data = json_decode($request->getContent(), true);
        $panier = $session->get('panier', []);
        $newTotal = 0;
        foreach ($panier as $key => &$p) {
            if (isset($p['produit']) && $p['produit']->getId() == $data['id'] && $p['couleur'] == $data['couleur'] && $p['taille'] == $data['taille']) {
                if ($p['quantity'] > 1) {
                    $p['quantity']--;
                } else {
                    unset($panier[$key]);
                    break;
                }
            }
        }

        foreach ($panier as &$p) {
            $newTotal += $p['produit']->getPrix() * $p['quantity'];
        }

        $count = count($panier);

        $session->set('panier', $panier);
        $session->set('nb', $count);

        return new JsonResponse(['message' => 'Votre article a bien été supprimé', 'nb' => $count, Response::HTTP_OK, 'total' => $newTotal]);
    }
}
