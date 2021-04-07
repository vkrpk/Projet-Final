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
        $query = $request->request->get('query');
        // if ($query) {
        $jeux = $jeuRepository->chercherJeu($query);
        return $this->render('annonce/chercher-annonce.html.twig', [
            'jeux' => $jeux
        ]);
    }
    // return $this->render('annonce/chercher-annonce.html.twig');
}
// }
    // public function formChercher(): Response
    // {
    //     $form = $this->createFormBuilder()
    //         ->setAction($this->generateUrl('home_chercher'))
    //         ->add('query', SearchType::class)
    //         ->add('submit', SubmitType::class)
    //         ->getForm();

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         return $this->render('parties-communes/navbar.html.twig', [
    //             'form' => $form->createView()
    //         ]);
    //     }
    //     return $this->render('parties-communes:navbar.html.twig');
    // }
