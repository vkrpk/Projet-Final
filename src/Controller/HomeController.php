<?php

namespace App\Controller;

use App\Repository\JeuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(JeuRepository $jeuRepository): Response
    {
        $jeu = $jeuRepository->findBy([], [], 1);
        // dd($jeu);

        return $this->render('home.html.twig', [
            'jeu' => $jeu
        ]);
    }
}
