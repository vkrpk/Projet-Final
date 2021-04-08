<?php

namespace App\Controller;

use DateTime;
use Exception;
use App\Entity\Jeu;
use App\Form\JeuType;
use App\Repository\JeuRepository;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AnnonceController extends AbstractController
{
    /**
     * @Route("/annonce/{slug}", name="annonce_montrer", priority=-1)
     */
    public function montrer(JeuRepository $jeuRepository, $slug): Response
    {
        $annonce = $jeuRepository->findOneBy([
            'slug' => $slug
        ]);

        if (!$annonce) {
            throw $this->createNotFoundException("La jeu demandé n'existe pas");
        }

        return $this->render('annonce/annonce.html.twig', [
            'a' => $annonce
        ]);
    }

    /**
     * @Route("/annonce/creer", name="annonce_creer")
     */
    public function creer(Request $request, SluggerInterface $slugger, EntityManagerInterface $em)
    {
        $annonce = new Jeu;

        $annonce->setDate(new DateTime('now'));

        $form = $this->createForm(JeuType::class, $annonce);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->get('photo')->getData();

            $fileName =  uniqid() . '.' . $photo->guessExtension();

            try {
                // On déplace le fichier
                $photo->move($this->getParameter('article_image_directory'), $fileName);
            } catch (FileException $ex) {
                $form->addError(new FormError('Une erreur est survenue pendant l\'upload du fichier : ' . $ex->getMessage()));
                throw new Exception('File upload error');
            }

            $annonce->setPhoto($fileName);

            $annonce->setSlug(strtolower($slugger->slug($annonce->getNom())));

            $em->persist($annonce);
            $em->flush();

            return $this->redirectToRoute('annonce_montrer', [
                'slug' => $annonce->getSlug()
            ]);
        }
        $formView = $form->createView();

        return $this->render('annonce/creer.html.twig', [
            'form' => $formView
        ]);
    }

    /**
     * @Route("/annonce/toutes", name="annonce_toutes")
     */
    public function toutes(JeuRepository $jeuRepository)
    {
        $jeux = $jeuRepository->findAll();
        return $this->render('annonce/toutes.html.twig', [
            'jeux' => $jeux
        ]);
    }
}
