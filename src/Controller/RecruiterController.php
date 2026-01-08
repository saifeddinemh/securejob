<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/recruiter')]
#[IsGranted('ROLE_RECRUITER')]
class RecruiterController extends AbstractController
{
    #[Route('/dashboard', name: 'recruiter_dashboard')]
    public function dashboard(): Response
    {
        $user = $this->getUser();
        $entreprise = $user->getEntreprise();

        if (!$entreprise) {
            return $this->redirectToRoute('recruiter_entreprise_edit');
        }

        $offres = $entreprise->getOffres();
        $totalCandidatures = 0;
        foreach ($offres as $offre) {
            $totalCandidatures += count($offre->getCandidatures());
        }

        return $this->render('recruiter/dashboard.html.twig', [
            'entreprise' => $entreprise,
            'offres' => $offres,
            'totalCandidatures' => $totalCandidatures,
        ]);
    }

    #[Route('/entreprise/edit', name: 'recruiter_entreprise_edit')]
    public function editEntreprise(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $entreprise = $user->getEntreprise();

        if (!$entreprise) {
            $entreprise = new Entreprise();
            $entreprise->setUser($user);
        }

        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($entreprise);
            $entityManager->flush();

            $this->addFlash('success', 'Informations de l\'entreprise mises à jour.');
            return $this->redirectToRoute('recruiter_dashboard');
        }

        return $this->render('recruiter/entreprise_edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
