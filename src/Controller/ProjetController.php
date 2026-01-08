<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Entity\Message;
use App\Form\ProjetType;
use App\Repository\ProjetRepository;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/marketplace/projets')]
class ProjetController extends AbstractController
{
    #[Route('/', name: 'app_projet_index', methods: ['GET'])]
    public function index(ProjetRepository $projetRepository): Response
    {
        return $this->render('projet/index.html.twig', [
            'projets' => $projetRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_projet_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_RECRUITER')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $projet = new Projet();
        $projet->setEntreprise($this->getUser()->getEntreprise());
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($projet);
            $entityManager->flush();

            return $this->redirectToRoute('app_projet_index');
        }

        return $this->render('projet/new.html.twig', [
            'projet' => $projet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_projet_show', methods: ['GET', 'POST'])]
    public function show(Projet $projet, Request $request, EntityManagerInterface $entityManager, MessageRepository $messageRepository): Response
    {
        // Gestion de la messagerie dans l'espace projet
        if ($request->isMethod('POST') && $this->getUser()) {
            $contenu = $request->request->get('message');
            if ($contenu) {
                $message = new Message();
                $message->setContenu($contenu);
                $message->setAuteur($this->getUser());
                $message->setProjet($projet);
                $entityManager->persist($message);
                $entityManager->flush();
                return $this->redirectToRoute('app_projet_show', ['id' => $projet->getId()]);
            }
        }

        $messages = $messageRepository->findBy(['projet' => $projet], ['createdAt' => 'ASC']);

        return $this->render('projet/show.html.twig', [
            'projet' => $projet,
            'messages' => $messages,
        ]);
    }

    #[Route('/{id}/rejoindre', name: 'app_projet_rejoindre', methods: ['POST'])]
    #[IsGranted('ROLE_CANDIDATE')]
    public function rejoindre(Projet $projet, EntityManagerInterface $entityManager): Response
    {
        $candidat = $this->getUser()->getCandidat(); // Supposant que User a une relation getCandidat()
        if ($candidat && $projet->getParticipants()->count() < $projet->getNombreParticipantsMax()) {
            $projet->addParticipant($candidat);
            $entityManager->flush();
            $this->addFlash('success', 'Vous avez rejoint le projet.');
        } else {
            $this->addFlash('error', 'Impossible de rejoindre le projet.');
        }

        return $this->redirectToRoute('app_projet_show', ['id' => $projet->getId()]);
    }
}
