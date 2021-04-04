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

        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('home_chercher'))
            ->add('query', SearchType::class)
            ->add('submit', SubmitType::class)
            ->getForm();

        return $this->render('home.html.twig', [
            'lastJeux' => $lastJeux,
            'form' => $form->createView()

        ]);
    }

    // public function formChercher(): Response
    // {
    //     $form = $this->createFormBuilder()
    //         ->setAction($this->generateUrl('home_chercher'))
    //         ->add('query', SearchType::class)
    //         ->add('submit', SubmitType::class)
    //         ->getForm();

    //     return $this->render('home.html.twig', [
    //         'form' => $form->createView()
    //     ]);
    // }

    /**
     * @Route("/chercher", name="home_chercher")
     * @param Request $request
     */
    public function chercher(Request $request, JeuRepository $jeuRepository): Response
    {
        $query = $request->request->get('form')['query'];
        if ($query) {
            $jeux = $jeuRepository->chercherJeu($query);
        }

        return $this->render('chercher.html.twig', [
            'jeux' => $jeux
        ]);
    }
}
