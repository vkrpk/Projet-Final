<?php

namespace App\Controller;

use App\Repository\JeuxRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(JeuxRepository $jeuxRepository): Response
    {
        $jeu = $jeuxRepository->find(1);

        return $this->render('home.html.twig', [
            'jeu' => $jeu
        ]);
    }
}
