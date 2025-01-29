<?php

namespace App\DataFixtures;



use App\Entity\Avis;
use App\Entity\Produits;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $produits = []; // Tableau pour stocker les produits

        for ($i = 0; $i < 5; $i++) {
            $produit = new Produits();
            $produit->setNomDuProduit("Produits " . ($i + 1));
            $produit->setDescription($faker->text(200)); // ✅ Ajoute une description aléatoire
            $produit->setPrix($faker->randomFloat(2, 50, 500)); // ✅ Ajoute un prix aléatoire

            $manager->persist($produit);
            $produits[] = $produit; // Ajoute chaque produit au tableau
        }



        $users = []; 

        for ($i = 0; $i < 3; $i++) {
            $user = new User();
            $user->setFirstname($faker->firstName); 
            $user->setEmail($faker->unique()->email); 
            $user->setPassword(password_hash('password123', PASSWORD_BCRYPT)); 

            $manager->persist($user);
            $users[] = $user; // Ajoute l'utilisateur au tableau
        }



        for ($i = 0; $i < 20; $i++) {
            $avis = new Avis();
            $avis->setNom($faker->name());
            $avis->setCommentaire($faker->text(200));
            $avis->setNote($faker->numberBetween(1, 5));

            // ✅ Sélectionne un produit aléatoire parmi ceux stockés dans le tableau $produits
            $avis->setProduits($faker->randomElement($produits));

            $manager->persist($avis);
        }

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
