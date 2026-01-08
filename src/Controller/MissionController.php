<?php

namespace App\Controller;

use App\Entity\Mission;
use App\Entity\Badge;
use App\Form\MissionType;
use App\Repository\MissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/marketplace/missions')]
class MissionController extends AbstractController
{
    #[Route('/', name: 'app_mission_index', methods: ['GET'])]
    public function index(MissionRepository $missionRepository): Response
    {
        return $this->render('mission/index.html.twig', [
            'missions' => $missionRepository->findBy(['statut' => 'ouverte']),
        ]);
    }

    #[Route('/new', name: 'app_mission_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_RECRUITER')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $mission = new Mission();
        $mission->setEntreprise($this->getUser()->getEntreprise());
        $form = $this->createForm(MissionType::class, $mission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($mission);
            $entityManager->flush();

            return $this->redirectToRoute('app_mission_index');
        }

        return $this->render('mission/new.html.twig', [
            'mission' => $mission,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mission_show', methods: ['GET'])]
    public function show(Mission $mission): Response
    {
        return $this->render('mission/show.html.twig', [
            'mission' => $mission,
        ]);
    }

    #[Route('/{id}/postuler', name: 'app_mission_postuler', methods: ['POST'])]
    #[IsGranted('ROLE_CANDIDATE')]
    public function postuler(Mission $mission, EntityManagerInterface $entityManager): Response
    {
        // Logique de postulation simplifiée
        $this->addFlash('success', 'Votre candidature a été envoyée.');
        return $this->redirectToRoute('app_mission_show', ['id' => $mission->getId()]);
    }

    #[Route('/{id}/terminer', name: 'app_mission_terminer', methods: ['POST'])]
    #[IsGranted('ROLE_RECRUITER')]
    public function terminer(Mission $mission, EntityManagerInterface $entityManager): Response
    {
        if ($mission->getEntreprise() !== $this->getUser()->getEntreprise()) {
            throw $this->createAccessDeniedException();
        }

        $mission->setStatut('terminee');
        
        // Attribution d'un badge au candidat assigné
        if ($mission->getCandidatAssigne()) {
            $badge = new Badge();
            $badge->setNom("Expert Mission: " . $mission->getTitre());
            $badge->setDescription("Badge obtenu pour la réussite de la mission.");
            // Note: Dans un système réel, liez le badge au candidat et à la compétence
            $mission->getCandidatAssigne()->addBadge($badge);
            $entityManager->persist($badge);
        }

        $entityManager->flush();
        $this->addFlash('success', 'Mission terminée et badge attribué.');

        return $this->redirectToRoute('app_mission_show', ['id' => $mission->getId()]);
    }
}
