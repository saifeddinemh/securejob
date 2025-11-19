<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Entity\User;
use App\Form\CandidateType;
use App\Repository\CandidateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/candidate')]
class CandidateController extends AbstractController
{
    #[Route('/', name: 'app_candidate_index', methods: ['GET'])]
    public function index(CandidateRepository $candidateRepository): Response
    {
        return $this->render('candidate/index.html.twig', [
            'candidates' => $candidateRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_candidate_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $candidate = new Candidate();

        // Assign a User manually for testing (replace 1 with your user ID)
        $user = $entityManager->getRepository(User::class)->find(1);
        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }
        $candidate->setUser($user);

        $form = $this->createForm(CandidateType::class, $candidate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($candidate);
            $entityManager->flush();

            return $this->redirectToRoute('app_candidate_index');
        }

        return $this->render('candidate/new.html.twig', [
            'candidate' => $candidate,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_candidate_show', methods: ['GET'])]
    public function show(int $id, CandidateRepository $candidateRepository): Response
    {
        $candidate = $candidateRepository->find($id);

        if (!$candidate) {
            throw $this->createNotFoundException('Candidate not found.');
        }

        return $this->render('candidate/show.html.twig', [
            'candidate' => $candidate,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_candidate_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request, CandidateRepository $candidateRepository, EntityManagerInterface $entityManager): Response
    {
        $candidate = $candidateRepository->find($id);

        if (!$candidate) {
            throw $this->createNotFoundException('Candidate not found.');
        }

        $form = $this->createForm(CandidateType::class, $candidate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_candidate_index');
        }

        return $this->render('candidate/edit.html.twig', [
            'candidate' => $candidate,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_candidate_delete', methods: ['POST'])]
    public function delete(int $id, Request $request, CandidateRepository $candidateRepository, EntityManagerInterface $entityManager): Response
    {
        $candidate = $candidateRepository->find($id);

        if (!$candidate) {
            throw $this->createNotFoundException('Candidate not found.');
        }

        if ($this->isCsrfTokenValid('delete'.$candidate->getId(), $request->request->get('_token'))) {
            $entityManager->remove($candidate);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_candidate_index');
    }

    #[Route('/profile/my', name: 'app_candidate_profile', methods: ['GET'])]
    public function profile(): Response
    {
        // For testing, just get the first Candidate linked to user id 1
        $candidate = $this->getDoctrine()->getRepository(Candidate::class)->findOneBy(['user' => 1]);

        if (!$candidate) {
            throw $this->createNotFoundException('Candidate profile not found.');
        }

        return $this->render('candidate/profile.html.twig', [
            'candidate' => $candidate,
        ]);
    }
}
