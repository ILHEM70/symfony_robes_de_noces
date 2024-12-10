<?php

namespace App\Controller;

use App\Form\ProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasherInterface): Response
    {
        $user = $this->getUser(); // Récupère l'utilisateur connecté
        $form = $this->createForm(ProfilType::class, $user); // Crée le formulaire de profil lié à l'utilisateur
        $form->handleRequest($request); // Traite la requête HTTP pour le formulaire


        if ($form->isSubmitted() && $form->isValid()) { // Vérifie si le formulaire a été soumis et validé
            $plainPassword = $form->get('password')->getData(); // Récupère le mot de passe en texte brut du formulaire

            if (!empty($plainPassword)) { // Si le mot de passe n'est pas vide, on le hache
                $hashedPassword = $passwordHasherInterface->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword); // Met à jour le mot de passe de l'utilisateur avec le mot de passe haché
            }

            try {
                $em->flush(); // Enregistre les modifications dans la base de données
                $this->addFlash('success', 'Votre profil a été mis à jour avec succès.'); // Ajoute un message de succès
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la mise à jour de votre profil.'); // Ajoute un message d'erreur
            }
        }

        return $this->render('profil/index.html.twig', [ // Rendu de la vue avec le formulaire
            'form' => $form->createView(),
            'bodyClass' => null,
        ]);
    }
}

