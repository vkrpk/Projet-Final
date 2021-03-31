<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\Jeu;
use Liior\Faker\Prices;
use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Bluemmb\Faker\PicsumPhotosProvider;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    protected $slugger;
    protected $nom;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    // public function createCategorie($nom, ObjectManager $manager)
    // {
    //     $this->nom = $nom;
    //     $categorie = new Categorie;
    //     $categorie->setNom($this->nom)
    //         ->setSlug(strtolower($this->slugger->slug($this->nom->getNom())));
    // }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new \Liior\Faker\Prices($faker));
        $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));
        $faker->addProvider(new \Bluemmb\Faker\PicsumPhotosProvider($faker));

        $console = ['Xbox', 'Nintendo 64', 'PlayStation 1', 'GameCube', 'NES'];
        for ($c = 0; $c < 5; $c++) {
            $categorie = new Categorie;
            $categorie->setNom($console[$c])
                ->setSlug(strtolower($this->slugger->slug($categorie->getNom())));
            $manager->persist($categorie);

            for ($j = 0; $j < mt_rand(15, 20); $j++) {
                $jeu = new Jeu;
                $jeu->setNom($faker->sentence(3))
                    ->setPrix($faker->price(100, 6000))
                    ->setDescription($faker->paragraph)
                    ->setDate(new DateTime())
                    ->setPhoto($faker->imageUrl(400, 400, true))
                    ->setSlug($this->slugger->slug($jeu->getNom()))
                    ->setCategorie($categorie);

                $manager->persist($jeu);
            }
        }
        $manager->flush();
    }
}
