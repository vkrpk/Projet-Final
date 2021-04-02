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
    public function home(JeuRepository $jeuRepository, Request $request, FormFactoryInterface $formFactory): Response
    {
        $lastJeux = $jeuRepository->findBy([], [], 1);

        $chercher = new Chercher();

        $form = $formFactory->create(ChercherType::class, $chercher);

        $form->handleRequest($request);

        $jeuChercher = $jeuRepository->chercherJeu($chercher);
        dump($jeuChercher[1]);

        return $this->render('home.html.twig', [
            'lastJeux' => $lastJeux,
            'form' => $form->createView(),
            'jeuxChercher' => $jeuChercher
        ]);
    }


    // $form = $this->createFormBuilder(null)
    //     ->add('search', TextType::class)
    //     ->getForm();




    /**
     * @Route("/chercher", name="home_chercher")
     * @param Request $request
     */
    public function chercher(Request $request): Response
    {
        // dd($request->request);
        return $this->render('chercher.html.twig');
    }
}
