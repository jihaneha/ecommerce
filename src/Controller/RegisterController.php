<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]

    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, EntityManagerInterface $em): Response
    {
        //je creer un utilisateur
        $user = new User();
        //je creer le formulaire correspondant
        $form = $this->createForm(RegisterType::class, $user);
        //je gère le formulaire 
        $form->handleRequest($request);
        //si le formulaire est bon je gère l'inscription
        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword(
                //hasher le mdp et le stocker en bdd
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $user = $form->getData();
            //je l'inscris dans la bdd
            $em->persist($user);
            //j'enregistre l'utilisateur
            $em->flush();
            $this->addFlash('success', 'Votre compte à été créer avec succée ');
            // je le redérige vers la page de connexion
            return $this->redirectToRoute('security_login');
        }
        $formView = $form->createView();
        return $this->render('register/index.html.twig', [
            'formView' => $formView
        ]);
    }
}
