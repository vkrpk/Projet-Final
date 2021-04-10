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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
     * @IsGranted("ROLE_USER")
     */
    public function creer(Request $request, SluggerInterface $slugger, EntityManagerInterface $em)
    {
        $annonce = new Jeu;

        $annonce->setDate(new DateTime('now'));
        $user = $this->getUser();
        $annonce->setUser($user);
        $form = $this->createForm(JeuType::class, $annonce);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->get('photo')->getData();

            $fileName =  uniqid() . '.' . $photo->guessExtension();

            try {
                // On déplace le fichier
                $photo->move($this->getParameter('annonce_image_directory'), $fileName);
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

    /**
     * @Route("/annonce/modifier/{id}", name="annonce_modifier")
     */
    public function modifier(Request $request, SluggerInterface $slugger, EntityManagerInterface $em, JeuRepository $jeuRepository, $id)
    {
        $annonce = $jeuRepository->find($id);
        $annonce->setDate(new DateTime('now'));
        $oldImage = $annonce->getPhoto();

        $form = $this->createForm(JeuType::class, $annonce);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('annonce/modifier.html.twig', [
                'form' => $form->createView(),
                'oldImage' => $oldImage,
                'id' => $annonce->getId()
            ]);
        } else {
            $photo = $form->get('photo')->getData();
            try {
                $photo->move($this->getParameter('annonce_image_directory'), $oldImage);
            } catch (FileException $ex) {
                $form->addError(new FormError('Une erreur est survenue pendant l\'upload du fichier : ' . $ex->getMessage()));
                throw new Exception('File upload error');
            }

            $annonce->setPhoto($oldImage);

            $annonce->setSlug(strtolower($slugger->slug($annonce->getNom())));

            $em->persist($annonce);
            $em->flush();

            return $this->redirectToRoute('annonce_montrer', [
                'slug' => $annonce->getSlug()
            ]);
        }
    }

    /**
     * @Route("/annonce/supprimer/{id}", name="annonce_supprimer")
     */
    public function supprimerArticle($id, JeuRepository $jeuRepository, EntityManagerInterface $em): Response
    {
        $annonce = $jeuRepository->find($id);

        if (empty($annonce)) throw new NotFoundHttpException();

        $em->remove($annonce);
        $em->flush();

        return $this->redirectToRoute('annonce_gerer');
    }

    /**
     * @Route("/annonce/gerer", name="annonce_gerer")
     * @IsGranted("ROLE_ADMIN", message="Vous n'avez pas le droit d'accéder à cette ressource")
     */
    public function gerer(JeuRepository $jeuRepository): Response
    {
        $annonce = $jeuRepository->findAll();

        return $this->render('annonce/gerer.html.twig', [
            'jeux' => $annonce
        ]);
    }
}
