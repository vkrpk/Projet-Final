<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\JeuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    /**
     * @Route("/{slug}", name="categorie_filtre", priority=-2)
     */
    public function filtre(CategorieRepository $categorieRepository, JeuRepository $jeuRepository,  $slug): Response
    {
        $categorie = $categorieRepository->findOneBy(['slug' => $slug]);

        if (!$categorie) {
            throw $this->createNotFoundException("La catégorie demandée n'existe pas");
        }

        $jeu = $jeuRepository->findBy(['categorie' => $categorie]);

        return $this->render('filtre-categorie.html.twig', [
            'categorie' => $categorie,
            'jeu' => $jeu
        ]);
    }
}
