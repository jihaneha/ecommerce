<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function index(): Response
    {
        return $this->render('account/profil.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }

    #[Route('account/changepassword', name: 'change_password')]
    public function changepassword(HttpFoundationRequest $request, UserPasswordHasherInterface $encoder, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $old_password = $form->get('old_password')->getData();
            if ($encoder->isPasswordValid($user, $old_password)) {

                $new_password = $form->get('new_password')->getData();

                $password = $encoder->hashPassword($user, $new_password);
                $user->setPassword($password);
                //mettre à jour le mdp
                $em->flush();
                //j'affiche un message à l'utilisateur
                $this->addFlash('success', 'Votre mot de passe à été modifié avec succée ');
            } else {
                $this->addFlash('warning', 'Votre mot de passe actuel est invalide');
            }
        }
        $user = $form->getData();
        return $this->render('account/changepassword.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
