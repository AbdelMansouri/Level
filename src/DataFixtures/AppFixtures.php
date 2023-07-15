<?php

namespace App\DataFixtures;

use App\Entity\Produit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    private Generator $faker;
    public function __construct()
    {
      $this->faker = Factory::create('fr_FR');
    }
    public function load(ObjectManager $manager): void
    {
        $produits = $this -> loadProduits($manager);
        $manager->flush();
    }
    private function loadProduits(ObjectManager $manager): array
    {
        $produits = [];
        for ($i = 0; $i < 20; $i++) {
            $produit = new Produit();
            $produit->setTitre($this->faker->sentence(3))
                    ->setDescription($this->faker->paragraph)
                    ->setCouleur($this->faker->safeColorName)
                    ->setTaille($this->faker->randomElement(['S', 'M', 'L', 'XL']))
                    ->setCollection($this->faker->word)
                    ->setPhoto($this->faker->numberBetween(1, 8) . '.jpg')
                    ->setPrix($this->faker->numberBetween(10, 100))
                    ->setStock($this->faker->numberBetween(0, 50))
                    ->setDateEnregistrement($this->faker->dateTimeThisYear);
            $manager->persist($produit);
            $produits[] = $produit;
        }
        return $produits;
    }
}
