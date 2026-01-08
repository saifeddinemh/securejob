<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Entreprise;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/auth/redirect', name: 'app_auth_redirect')]
    public function authRedirect(): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin_dashboard');
        }
        return $this->redirectToRoute('recruiter_dashboard');
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            if ($this->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('admin_dashboard');
            }
            return $this->redirectToRoute('recruiter_dashboard');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/register/recruiter', name: 'app_register_recruiter')]
    public function registerRecruiter(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $companyName = $request->request->get('company_name');

            $user = new User();
            $user->setEmail($email);
            $user->setRoles(['ROLE_RECRUITER']);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $password
                )
            );

            $entreprise = new Entreprise();
            $entreprise->setNom($companyName);
            $entreprise->setUser($user);

            $entityManager->persist($user);
            $entityManager->persist($entreprise);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/register_recruiter.html.twig');
    }
}
