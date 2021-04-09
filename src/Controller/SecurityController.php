<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $utils): Response
    {
        $form = $this->createForm(LoginType::class, ['email' => $utils->getLastUsername()]);

        return $this->render('security/login.html.twig', [
            'form' => $form->createView(),
            'error' => $utils->getLastAuthenticationError()
        ]);
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout()
    {
    }

    /**
     * @Route("/register", name="security_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $em)
    {

        $form = $this->createFormBuilder()
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Email()
                ]
            ])
            ->add('pseudo', TextType::class, [
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => [
                    'label' => 'Mot de passe',
                    'constraints' => [
                        new NotBlank(),
                        new Length([
                            'min' => 8
                        ])
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmation du mot de passe'
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Créer un compte'
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = new User();

            $formData = $form->getData();

            $encodedPassword = $encoder->encodePassword($user, $formData['password']);

            $user->setPassword($encodedPassword);
            $user->setPseudo($formData['pseudo']);
            $user->setEmail($formData['email']);

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('security_login');
        } else {
            return $this->render('security/register.html.twig', [
                'form' => $form->createView()
            ]);
        }
    }
}
