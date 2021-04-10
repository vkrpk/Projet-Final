<?php

namespace App\Controller;

use App\Repository\JeuRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(JeuRepository $jeuRepository): Response
    {
        $lastJeux = $jeuRepository->findBy([], [], 3);

        return $this->render('home/home.html.twig', [
            'lastJeux' => $lastJeux,
        ]);
    }

    /**
     * @Route("/rechercher/annonce", name="home_chercher")
     * @param Request $request
     */
    public function chercher(Request $request, JeuRepository $jeuRepository): Response
    {
        $submittedToken = $request->request->get('_csrf_token');
        $query = $request->request->get('query');
        if ($this->isCsrfTokenValid('token-name', $submittedToken) && !empty($query)) {
            $jeux = $jeuRepository->chercherJeu($query);
            return $this->render('annonce/chercher-annonce.html.twig', [
                'jeux' => $jeux
            ]);
        }
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/cgu", name="home_cgu")
     */
    public function cgu(): Response
    {
        return $this->render('parties-communes/cgu.html.twig');
    }

    /**
     * @Route("/a-propos", name="home_apropos")
     */
    public function aPropos(): Response
    {
        return $this->render('parties-communes/a-propos.html.twig');
    }
}
