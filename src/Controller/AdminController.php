<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Candidat;
use App\Entity\Entreprise;
use App\Entity\Mission;
use App\Entity\Projet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin', name: 'admin_')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $recruitersCount = $entityManager->getRepository(Entreprise::class)->count([]);
        $candidatesCount = $entityManager->getRepository(Candidat::class)->count([]);
        $missionsCount = $entityManager->getRepository(Mission::class)->count([]);
        $projectsCount = $entityManager->getRepository(Projet::class)->count([]);

        return $this->render('admin/dashboard.html.twig', [
            'recruiters_count' => $recruitersCount,
            'candidates_count' => $candidatesCount,
            'missions_count' => $missionsCount,
            'projects_count' => $projectsCount,
        ]);
    }

    #[Route('/recruiters', name: 'recruiters')]
    public function recruiters(EntityManagerInterface $entityManager): Response
    {
        $recruiters = $entityManager->getRepository(Entreprise::class)->findAll();

        return $this->render('admin/recruiters.html.twig', [
            'recruiters' => $recruiters,
        ]);
    }

    #[Route('/candidates', name: 'candidates')]
    public function candidates(EntityManagerInterface $entityManager): Response
    {
        $candidates = $entityManager->getRepository(Candidat::class)->findAll();

        return $this->render('admin/candidates.html.twig', [
            'candidates' => $candidates,
        ]);
    }
}
