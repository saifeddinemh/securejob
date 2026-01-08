<?php

namespace App\Controller;

use App\Entity\OffreEmploi;
use App\Form\OffreEmploiType;
use App\Repository\OffreEmploiRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/recruiter/offres')]
#[IsGranted('ROLE_RECRUITER')]
class OffreEmploiController extends AbstractController
{
    #[Route('/', name: 'recruiter_offres_index', methods: ['GET'])]
    public function index(): Response
    {
        $entreprise = $this->getUser()->getEntreprise();
        return $this->render('recruiter/offres/index.html.twig', [
            'offres' => $entreprise->getOffres(),
        ]);
    }

    #[Route('/new', name: 'recruiter_offres_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $offre = new OffreEmploi();
        $offre->setEntreprise($this->getUser()->getEntreprise());
        
        $form = $this->createForm(OffreEmploiType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($offre);
            $entityManager->flush();

            $this->addFlash('success', 'Offre d\'emploi créée avec succès.');
            return $this->redirectToRoute('recruiter_offres_index');
        }

        return $this->render('recruiter/offres/new.html.twig', [
            'offre' => $offre,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'recruiter_offres_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, OffreEmploi $offre, EntityManagerInterface $entityManager): Response
    {
        // Security check: ensure the recruiter owns this offer
        if ($offre->getEntreprise() !== $this->getUser()->getEntreprise()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(OffreEmploiType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Offre d\'emploi mise à jour.');
            return $this->redirectToRoute('recruiter_offres_index');
        }

        return $this->render('recruiter/offres/edit.html.twig', [
            'offre' => $offre,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'recruiter_offres_delete', methods: ['POST'])]
    public function delete(Request $request, OffreEmploi $offre, EntityManagerInterface $entityManager): Response
    {
        if ($offre->getEntreprise() !== $this->getUser()->getEntreprise()) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete'.$offre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($offre);
            $entityManager->flush();
            $this->addFlash('success', 'Offre d\'emploi supprimée.');
        }

        return $this->redirectToRoute('recruiter_offres_index');
    }
}
