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
    #[Route("/cart", name: "app_cart", methods: ['POST'])]
    public function add(SessionInterface $session, ProduitsRepository $produitsRepository, Request $request): JsonResponse
    {
        // Vérifie si l'utilisateur est connecté. Si non, renvoie un message d'erreur
        if (!$this->getUser()) {
            return new JsonResponse(['error' => 'Merci de vous connecter pour ajouter un produit au panier !']);
        }
       
        // Récupère le corps de la requête HTTP ,
        // le décode depuis le format JSON en un tableau associatif PHP. Cela permet d'accéder
        // facilement aux données envoyées par le client.
        $data = json_decode($request->getContent(), true);
         // Si le produit n'est pas dans le panier on crée un tableau qui pourrai stocker le nouveau produit.
        // Extrait l'ID du produit, la couleur, la taille et l'image du produit à partir des données de la requête
        $id = $data['id'];
        $couleur = $data['couleur'];
        $taille = $data['taille'];
        $image = $data['image'];


        // Vérifie que l'ID du produit est présent, sinon renvoie un message d'erreur
        if (!$id) {
            return new JsonResponse(['error' => 'ID de produit manquant.'], Response::HTTP_BAD_REQUEST);
        }

        // Recherche le produit dans la base de données en fonction de son ID
        $robe = $produitsRepository->find($id);

        // Si le produit n'est pas trouvé dans la base de données, renvoie un message d'erreur
        if (!$robe) {
            return new JsonResponse(['error' => 'Produit introuvable.'], Response::HTTP_NOT_FOUND);
        }

        // Récupère le panier de la session, ou initialise un tableau vide si le panier n'existe pas encore
        $panier = $session->get('panier', []);
        // récupère ma session 
        // Initialise une variable pour savoir si le produit est déjà dans le panier
        $found = false;

        // Parcourt les éléments du panier pour vérifier si le produit existe déjà avec la même couleur et taille
        foreach ($panier as &$item) {
            if ($item['produit']->getId() === $id && $item['couleur'] === $couleur && $item['taille'] === $taille) {
                // Si le produit est trouvé, on augmente la quantité de 1

                $item['quantity'] += 1;
                $found = true;
                break; // Sort de la boucle une fois le produit trouvé
            }
        }

        // Si le produit n'est pas trouvé dans le panier, on l'ajoute avec une quantité de 1
        if (!$found) {
            $panier[] = [
                'produit' => $robe,      // Produit ajouté au panier
                'quantity' => 1,         // Quantité du produit (initialisée à 1)
                'couleur' => $couleur,   // Couleur sélectionnée
                'taille' => $taille,     // Taille sélectionnée
                'image' => $image        // Image du produit
            ];
        }

        // Calcule le nombre d'articles dans le panier
        $count = count($panier);

        // Met à jour la session avec le nombre d'articles et le panier modifié
        $session->set('nb', $count);
        $session->set('panier', $panier);

        // Renvoie une réponse JSON indiquant que le produit a été ajouté avec succès et retourne le nombre d'articles dans le panier
        return new JsonResponse(['message' => 'Produit ajouté au panier.', 'nb' => $count], Response::HTTP_OK);
    }

    #[Route("/panier", name: "app_panier")]
    public function show(SessionInterface $session): Response
    {
        // Récupère le panier depuis la session, ou un tableau vide s'il n'existe pas
        $panier = $session->get('panier', []);

        // Calcule le total du panier en multipliant le prix de chaque produit par sa quantité
        $total = array_reduce($panier, function ($sum, $item) {
            // Récupère le prix du produit ou 0 si le prix n'est pas défini
            $price = $item['produit']->getPrix() ?? 0;
            // Récupère la quantité du produit ou 0 si la quantité n'est pas définie
            $quantity = $item['quantity'] ?? 0;
            // Retourne la somme totale en ajoutant le prix multiplié par la quantité
            return $sum + $price * $quantity;
        }, 0);

        // Rendre la vue du panier, avec les informations du panier et le total
        return $this->render('cart/index.html.twig', [
            'panier' => $panier,   // Transmet le panier à la vue
            'total' => $total,     // Transmet le total du panier à la vue
            'bodyClass' => null,   // (Optionnel) Peut être utilisé pour ajouter une classe CSS au corps de la page
        ]);
    }

    #[Route("/panier/supp", name: "cart_remove")]
    public function removeItem(Request $request, SessionInterface $session)
    {
        // Récupère les données de la requête JSON envoyées par le client
        $data = json_decode($request->getContent(), true);

        // Récupère le panier depuis la session
        $panier = $session->get('panier', []);
        // Initialise la variable pour recalculer le total du panier après modification
    }
}
