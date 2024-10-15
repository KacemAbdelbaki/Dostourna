<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    #[Route('/login', name: 'app_user_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('user/login.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route(path: '/logout', name: 'app_user_logout')]
    public function logout(): void
    {
        throw new \LogicException('logged out');
    }
    #[Route(path: '/profile', name: 'app_user_profile')]
    public function profile(): Response
    {
        return $this->render('user/profile.html.twig', [
            'controller_name' => 'UserController',
            'part' => 0,
            'title' => 'Mon Profil',
            'titlepage' => 'Profile - ',
            'stripe_public_key' => $this->getParameter('app.stripe_public_key'),

        ]);
    }
    #[Route('/register', name: 'app_user_register')]
    public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        if ($request->isMethod('POST')) {
            $user = new User(); 
            $user->setFirstName($request->request->get('firstName'));
            $user->setLastName($request->request->get('lastName'));
            $user->setEmail($request->request->get('email'));
            $hashedPassword = $passwordHasher->hashPassword($user, $request->request->get('password'));
            $user->setPassword($hashedPassword);
            $user->setPhone($request->request->get('phone'));
            $user->setBalance(0);

            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_user_login');
        }
        return $this->render('user/register.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
