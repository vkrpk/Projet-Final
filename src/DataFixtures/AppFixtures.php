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
        $jeux = [
            'Super Mario Bros', 'Sonic', 'Zelda : ocarina of time', 'Mario Kart 64', 'Super Smash Bros Melee', 'Dead or Alive', 'Dead or Alive Xtreme Beach Volley Ball', 'Enter the Matrix', 'Max Payne 2', 'Yoshi\'s Island', 'Commandos 3', 'Final Fantasy X', 'Pokemon Rubis',  'Starcraft',  'Grand Theft Auto 3', 'Homeworld 2', 'Aladin',  'Super Mario Bros 3', 'SSX 3', 'Star Wars : Jedi outcast', 'Actua Soccer 3', 'Time Crisis 3', 'X-FILES',
            'Soul Calibur 2', 'Diablo',  'Street Fighter 2', 'Gundam Battle Assault 2', 'Spider-Man', 'Midtown Madness 3', 'Tetris', 'The Rocketeer', 'Pro Evolution Soccer 3', 'Ice Hockey', 'Sydney 2000', 'NBA 2k', 'Aliens Versus Predator : Extinction', 'Crazy Taxi', 'Le Maillon Faible', 'FIFA 64', 'Qui Veut Gagner Des Millions', 'Monopoly', 'Taxi 3', 'Indiana Jones Et Le Tombeau De L\'Empereur',  'F-ZERO', 'Harry Potter Et La Chambre Des Secrets',  'Half-Life',  'Myst III Exile', 'Wario World',  'Rollercoaster Tycoon',  'Splinter Cell'
        ];
        $value = $jeux[array_rand($jeux)];
        for ($c = 0; $c < 5; $c++) {
            $categorie = new Categorie;
            $categorie->setNom($console[$c])
                ->setSlug(strtolower($this->slugger->slug($categorie->getNom())));
            $manager->persist($categorie);

            for ($j = 0; $j < mt_rand(7, 15); $j++) {
                $start    = new Datetime('1st December 2020');
                $end      = new Datetime();
                $random   = new DateTime('@' . mt_rand($start->getTimestamp(), $end->getTimestamp()));

                $jeu = new Jeu;
                $jeu->setNom($jeux[array_rand($jeux)])
                    ->setPrix($faker->price(100, 6000))
                    ->setDescription($faker->paragraph)
                    ->setDate($random)
                    ->setLieu($faker->city)
                    ->setPhoto("categorie$c.jpg")
                    ->setSlug($this->slugger->slug($jeu->getNom()))
                    ->setCategorie($categorie);

                $manager->persist($jeu);
            }
        }
        $manager->flush();
    }
}
