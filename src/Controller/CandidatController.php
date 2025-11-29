<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Entity\Experience;
use App\Entity\Formation;
use App\Entity\Langue;
use App\Entity\Competence;
use App\Entity\Badge;
use App\Form\CandidatType;
use Doctrine\ORM\EntityManagerInterface;
use Smalot\PdfParser\Parser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/candidat', name: 'candidat_')]
class CandidatController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        // Récupération de toutes les compétences et badges pour les filtres
        $competences = $em->getRepository(Competence::class)->findAll();
        $badges = $em->getRepository(Badge::class)->findAll();

        // Filtres GET
        $selectedCompetence = $request->query->get('competence');
        $selectedBadge = $request->query->get('badge');
        $search = $request->query->get('search');

        // QueryBuilder
        $qb = $em->getRepository(Candidat::class)->createQueryBuilder('c');

        if ($selectedCompetence) {
            $qb->join('c.competences', 'comp')
                ->andWhere('comp.id = :compId')
                ->setParameter('compId', $selectedCompetence);
        }

        if ($selectedBadge) {
            $qb->join('c.badges', 'b')
                ->andWhere('b.id = :badgeId')
                ->setParameter('badgeId', $selectedBadge);
        }

        if ($search) {
            $qb->andWhere('c.nom LIKE :search OR c.prenom LIKE :search OR c.email LIKE :search')
                ->setParameter('search', '%'.$search.'%');
        }

        $candidats = $qb->getQuery()->getResult();

        return $this->render('candidat/index.html.twig', [
            'candidats' => $candidats,
            'competences' => $competences,
            'badges' => $badges,
            'selectedCompetence' => $selectedCompetence,
            'selectedBadge' => $selectedBadge,
            'search' => $search,
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $candidat = new Candidat();
        $form = $this->createForm(CandidatType::class, $candidat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Upload CV
            $cvFile = $form->get('cvFile')->getData();
            if ($cvFile) {
                $newFilename = uniqid().'_'.$cvFile->getClientOriginalName();
                try {
                    $cvFile->move($this->getParameter('cv_directory'), $newFilename);
                    $candidat->setCvPath($newFilename);

                    // Extraction automatique du contenu
                    $cvPath = $this->getParameter('cv_directory').'/'.$newFilename;
                    $extension = strtolower($cvFile->getClientOriginalExtension());
                    $text = '';

                    if ($extension === 'pdf') {
                        $parser = new Parser();
                        $pdf = $parser->parseFile($cvPath);
                        $text = $pdf->getText();
                    } else {
                        $text = file_get_contents($cvPath);
                    }

                    $lines = explode("\n", $text);
                    $section = '';
                    foreach ($lines as $line) {
                        $line = trim($line);
                        if (empty($line)) continue;

                        if (stripos($line, 'Expériences:') !== false) { $section='experience'; continue; }
                        if (stripos($line, 'Formations:') !== false) { $section='formation'; continue; }
                        if (stripos($line, 'Langues:') !== false) { $section='langue'; continue; }

                        switch ($section) {
                            case 'experience':
                                $exp = new Experience();
                                if (preg_match('/(.+) chez (.+)/i', $line, $m)) {
                                    $exp->setPoste(trim($m[1]));
                                    $exp->setEntreprise(trim($m[2]));
                                } else {
                                    $exp->setPoste($line);
                                    $exp->setEntreprise('Entreprise inconnue');
                                }
                                $exp->setDateDebut(new \DateTime());
                                $exp->setDateFin(null);
                                $candidat->addExperience($exp);
                                break;

                            case 'formation':
                                $formation = new Formation();
                                $formation->setTitre($line);
                                $formation->setEtablissement('Inconnu');
                                $formation->setDateDebut(new \DateTime());
                                $formation->setDateFin(null);
                                $candidat->addFormation($formation);
                                break;

                            case 'langue':
                                $langue = $em->getRepository(Langue::class)->findOneBy(['nom'=>trim($line)]);
                                if ($langue) { $candidat->addLangue($langue); }
                                break;

                            default:
                                if (stripos($line,'Nom:')===0) $candidat->setNom(trim(substr($line,4)));
                                if (stripos($line,'Prénom:')===0) $candidat->setPrenom(trim(substr($line,7)));
                                if (stripos($line,'Email:')===0) $candidat->setEmail(trim(substr($line,6)));
                                if (stripos($line,'Téléphone:')===0) $candidat->setTelephone(trim(substr($line,10)));
                                if (stripos($line,'Date de naissance:')===0) {
                                    try { $candidat->setDateNaissance(new \DateTime(trim(substr($line,18)))); }
                                    catch (\Exception $e) { $candidat->setDateNaissance(new \DateTime('2000-01-01')); }
                                }
                                if (stripos($line,'Adresse:')===0) $candidat->setAdresse(trim(substr($line,8)));
                                break;
                        }
                    }

                } catch (\Exception $e) {
                    $this->addFlash('warning', 'Impossible de lire le CV: '.$e->getMessage());
                }
            }

            // Upload Photo
            $photoFile = $form->get('photoFile')->getData();
            if ($photoFile) {
                $newFilename = uniqid().'_'.$photoFile->getClientOriginalName();
                try {
                    $photoFile->move($this->getParameter('photo_directory'), $newFilename);
                    $candidat->setPhotoPath($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de la photo.');
                }
            }

            $em->persist($candidat);
            $em->flush();
            $this->addFlash('success', 'Candidat ajouté avec succès !');

            return $this->redirectToRoute('candidat_index');
        }

        return $this->render('candidat/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'show')]
    public function show(Candidat $candidat): Response
    {
        return $this->render('candidat/show.html.twig', [
            'candidat' => $candidat
        ]);
    }

    #[Route('/{id}/edit', name: 'edit')]
    public function edit(Request $request, Candidat $candidat, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CandidatType::class, $candidat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Candidat modifié avec succès !');
            return $this->redirectToRoute('candidat_index');
        }

        return $this->render('candidat/edit.html.twig', [
            'form' => $form->createView(),
            'candidat' => $candidat
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Candidat $candidat, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$candidat->getId(), $request->request->get('_token'))) {
            $em->remove($candidat);
            $em->flush();
            $this->addFlash('success', 'Candidat supprimé avec succès !');
        }

        return $this->redirectToRoute('candidat_index');
    }
}
