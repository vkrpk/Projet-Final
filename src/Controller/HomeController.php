<?php

namespace App\Controller;

use App\Entity\Chercher;
use App\Form\ChercherType;
use App\Form\SearchType;
use App\Repository\JeuRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

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

    /**
     * @Route("/chercher", name="home_chercher")
     */
    public function chercher(JeuRepository $jeuRepository, Request $request, FormFactoryInterface $formFactory): Response
    {
        $chercher = new Chercher();

        $form = $formFactory->create(ChercherType::class, $chercher);

        $form->handleRequest($request);

        $jeu = $jeuRepository->chercherJeu($chercher);
        // dd($jeu);
        return $this->render('chercher.html.twig', [
            'jeux' => $jeu,
            'form' => $form->createView()
        ]);
    }
}
