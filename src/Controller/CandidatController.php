<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Entity\Experience;
use App\Entity\Formation;
use App\Entity\Langue;
use App\Entity\Competence;
use App\Entity\Badge;
use App\Form\CandidatType;
use App\Form\EntretienType;
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
    // ==================== ROUTES SIMULATEUR (EN PREMIER !) ====================

    #[Route('/simulateur', name: 'simulateur_index')]
    public function simulateurIndex(): Response
    {
        return $this->render('simulateur/index.html.twig');
    }

    #[Route('/simulateur/entretien/{id}', name: 'simulateur_entretien')]
    public function simulateurEntretien(Candidat $candidat): Response
    {
        return $this->render('simulateur/entretien.html.twig', [
            'candidat' => $candidat
        ]);
    }

    #[Route('/simulateur/technique', name: 'simulateur_technique', methods: ['GET', 'POST'])]
    public function entretienTechnique(Request $request): Response
    {
        $questions = [
            'Développeur Web' => [
                'junior' => [
                    'Qu\'est-ce que le HTML et à quoi sert-il ?',
                    'Expliquez la différence entre HTTP et HTTPS',
                    'Qu\'est-ce qu\'une variable en programmation ?',
                    'Quelle est la différence entre frontend et backend ?'
                ],
                'intermediaire' => [
                    'Comment optimisez-vous les performances d\'un site web ?',
                    'Expliquez le concept de REST API',
                    'Quelles sont les bonnes pratiques de sécurité web ?',
                    'Comment gérez-vous les dépendances dans un projet ?'
                ],
                'senior' => [
                    'Décrivez l\'architecture d\'une application microservices',
                    'Comment gérez-vous la scalabilité horizontale ?',
                    'Quelles sont les tendances futures du développement web ?',
                    'Comment concevoir une API durable et évolutive ?'
                ]
            ],
            'Chef de Projet' => [
                'junior' => [
                    'Qu\'est-ce qu\'un diagramme de Gantt ?',
                    'Comment gérez-vous les délais serrés ?',
                    'Quels outils de gestion de projet connaissez-vous ?'
                ],
                'intermediaire' => [
                    'Décrivez votre expérience avec Agile/Scrum',
                    'Comment gérez-vous les conflits d\'équipe ?',
                    'Comment priorisez-vous les tâches ?'
                ],
                'senior' => [
                    'Quelle est votre stratégie pour gérer les parties prenantes difficiles ?',
                    'Comment mesurez-vous le ROI d\'un projet ?',
                    'Comment gérez-vous un projet en retard et dépassant le budget ?'
                ]
            ]
        ];

        $resultats = null;

        if ($request->isMethod('POST')) {
            $metier = $request->request->get('metier', 'Développeur Web');
            $niveau = $request->request->get('niveau', 'junior');

            $scoreBase = [
                'junior' => 70,
                'intermediaire' => 75,
                'senior' => 80
            ];

            $score = $scoreBase[$niveau] ?? 70;
            $score += rand(-10, 15);
            $score = min(100, max(40, $score));

            $resultats = [
                'metier' => $metier,
                'niveau' => $niveau,
                'score' => $score,
                'questions' => $questions[$metier][$niveau] ?? [],
                'feedback' => $this->genererFeedback($score)
            ];
        }

        return $this->render('simulateur/technique.html.twig', [
            'questions' => $questions,
            'resultats' => $resultats
        ]);
    }

    #[Route('/simulateur/comportemental', name: 'simulateur_comportemental', methods: ['GET', 'POST'])]
    public function simulationComportementale(Request $request): Response
    {
        $scenarios = [
            [
                'id' => 1,
                'titre' => 'Gestion de conflit',
                'description' => 'Un collègue critique publiquement votre travail lors d\'une réunion. Comment réagissez-vous ?',
                'bonnes_pratiques' => [
                    'Rester calme et professionnel',
                    'Demander un entretien privé après la réunion',
                    'Écouter les critiques constructives',
                    'Proposer des solutions plutôt que de se défendre'
                ]
            ],
            [
                'id' => 2,
                'titre' => 'Pression des délais',
                'description' => 'Votre équipe risque de ne pas respecter une deadline importante. Quelle est votre approche ?',
                'bonnes_pratiques' => [
                    'Communiquer rapidement avec le client/manager',
                    'Prioriser les tâches essentielles',
                    'Demander des ressources supplémentaires si nécessaire',
                    'Proposer un plan de repli réaliste'
                ]
            ]
        ];

        $feedback = null;

        if ($request->isMethod('POST')) {
            $reponses = $request->request->all();

            $score = 0;
            $totalReponses = 0;
            $conseils = [];
            $points_forts = [];

            foreach ($scenarios as $scenario) {
                $reponseKey = 'scenario_' . $scenario['id'];
                if (isset($reponses[$reponseKey])) {
                    $totalReponses++;
                    $reponse = $reponses[$reponseKey];
                    $longueur = strlen(trim($reponse));

                    if ($longueur > 150) {
                        $score += 30;
                        $points_forts[] = "Scénario '{$scenario['titre']}' : réponse très détaillée";
                    } elseif ($longueur > 80) {
                        $score += 20;
                        $points_forts[] = "Scénario '{$scenario['titre']}' : réponse complète";
                    } elseif ($longueur > 30) {
                        $score += 10;
                    } else {
                        $conseils[] = "Scénario '{$scenario['titre']}' : réponse trop courte";
                    }

                    $motsPertinents = ['communication', 'écouter', 'solution', 'plan', 'objectif', 'équipe', 'collaboration'];
                    foreach ($motsPertinents as $mot) {
                        if (stripos($reponse, $mot) !== false) {
                            $score += 5;
                            break;
                        }
                    }
                }
            }

            $scoreMax = $totalReponses * 35;
            $pourcentage = $scoreMax > 0 ? round(($score / $scoreMax) * 100) : 0;
            $pourcentage = min(100, $pourcentage);

            $feedback = [
                'score' => $pourcentage,
                'total_scenarios' => $totalReponses,
                'points_forts' => array_slice($points_forts, 0, 3),
                'conseils' => array_slice($conseils, 0, 3),
                'evaluation' => $this->evaluationScore($pourcentage),
                'recommendation' => $this->getRecommendation($pourcentage)
            ];
        }

        return $this->render('simulateur/comportemental.html.twig', [
            'scenarios' => $scenarios,
            'feedback' => $feedback
        ]);
    }

    #[Route('/simulateur/test', name: 'simulateur_test', methods: ['GET', 'POST'])]
    public function evaluationTechnique(Request $request): Response
    {
        $tests = [
            'frontend' => [
                [
                    'id' => 'frontend_1',
                    'question' => 'Quelle est la différence entre let, const et var en JavaScript ?',
                    'type' => 'qcm',
                    'options' => [
                        'A) Tous sont identiques',
                        'B) var a une portée fonction, let/const ont une portée bloc',
                        'C) let peut être réassigné, const non',
                        'D) B et C sont corrects'
                    ],
                    'reponse' => 'D'
                ]
            ],
            'backend' => [
                [
                    'id' => 'backend_1',
                    'question' => 'Comment prévenir les injections SQL ?',
                    'type' => 'qcm',
                    'options' => [
                        'A) Utiliser des requêtes préparées',
                        'B) Échapper les entrées utilisateur',
                        'C) Les deux',
                        'D) Aucune des réponses'
                    ],
                    'reponse' => 'C'
                ]
            ]
        ];

        $correction = null;

        if ($request->isMethod('POST')) {
            $reponses = $request->request->all();
            $score = 0;
            $total = 0;
            $details = [];

            foreach ($tests as $categorie => $questions) {
                foreach ($questions as $q) {
                    $total++;

                    if (isset($reponses[$q['id']])) {
                        $reponseUtilisateur = trim($reponses[$q['id']]);

                        if ($q['type'] === 'qcm' && $reponseUtilisateur === $q['reponse']) {
                            $score++;
                            $details[] = "✅ <strong>Question {$total}</strong> : Correcte";
                        } elseif ($q['type'] === 'qcm') {
                            $details[] = "❌ <strong>Question {$total}</strong> : Incorrecte";
                        }
                    } else {
                        $details[] = "⏰ <strong>Question {$total}</strong> : Non répondue";
                    }
                }
            }

            $pourcentage = $total > 0 ? round(($score / $total) * 100) : 0;

            $correction = [
                'score' => $pourcentage,
                'total' => $total,
                'correctes' => $score,
                'details' => $details,
                'niveau' => $this->determinerNiveau($pourcentage)
            ];
        }

        return $this->render('simulateur/test.html.twig', [
            'tests' => $tests,
            'correction' => $correction
        ]);
    }

    #[Route('/simulateur/feedback', name: 'simulateur_feedback', methods: ['GET', 'POST'])]
    public function feedbackIA(Request $request): Response
    {
        $analyse = null;

        if ($request->isMethod('POST')) {
            $texte = trim($request->request->get('texte_reponse', ''));

            if (!empty($texte)) {
                $mots = str_word_count($texte);
                $phrases = substr_count($texte, '.') + substr_count($texte, '!') + substr_count($texte, '?');

                $scoreStructure = 0;
                if ($mots > 100) $scoreStructure += 30;
                elseif ($mots > 50) $scoreStructure += 20;
                elseif ($mots > 20) $scoreStructure += 10;

                if ($phrases > 3) $scoreStructure += 20;

                $scoreStructure = min(100, $scoreStructure);

                $analyse = [
                    'statistiques' => [
                        'longueur' => $mots . ' mots',
                        'phrases' => $phrases
                    ],
                    'scores' => [
                        'structure' => $scoreStructure,
                        'global' => $scoreStructure
                    ],
                    'points_forts' => $this->detecterPointsForts($texte, $mots, $phrases),
                    'suggestions' => $this->genererSuggestions($mots, $phrases),
                    'evaluation' => $this->evaluationScore($scoreStructure)
                ];
            }
        }

        return $this->render('simulateur/feedback.html.twig', [
            'analyse' => $analyse
        ]);
    }

    // ==================== ROUTES CANDIDATS (APRÈS LES ROUTES SIMULATEUR !) ====================

    #[Route('/', name: 'index')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $competences = $em->getRepository(Competence::class)->findAll();
        $badges = $em->getRepository(Badge::class)->findAll();

        $selectedCompetence = $request->query->get('competence');
        $selectedBadge = $request->query->get('badge');
        $search = $request->query->get('search');

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
            $cvFile = $form->get('cvFile')->getData();
            if ($cvFile) {
                $newFilename = uniqid().'_'.$cvFile->getClientOriginalName();
                try {
                    $cvFile->move($this->getParameter('cv_directory'), $newFilename);
                    $candidat->setCvPath($newFilename);

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

    // ==================== METHODES PRIVEES D'AIDE ====================

    private function genererFeedback(int $score): array
    {
        if ($score >= 90) {
            return [
                'titre' => 'Excellent ! 🏆',
                'message' => 'Vos réponses démontrent une excellente maîtrise technique.',
                'conseil' => 'Continuez à vous tenir informé des nouvelles technologies.',
                'icon' => '🏆',
                'couleur' => '#4caf50'
            ];
        } elseif ($score >= 70) {
            return [
                'titre' => 'Bon travail ! 💪',
                'message' => 'Bonnes bases techniques avec quelques points à renforcer.',
                'conseil' => 'Pratiquez davantage et étudiez les cas limites.',
                'icon' => '💪',
                'couleur' => '#ffc107'
            ];
        } else {
            return [
                'titre' => 'À travailler 📚',
                'message' => 'Des bases à consolider pour progresser.',
                'conseil' => 'Revoyez les fondamentaux et faites plus d\'exercices pratiques.',
                'icon' => '📚',
                'couleur' => '#ff9800'
            ];
        }
    }

    private function evaluationScore(int $score): string
    {
        if ($score >= 80) return 'Excellent';
        if ($score >= 60) return 'Bon';
        return 'À améliorer';
    }

    private function determinerNiveau(int $score): string
    {
        if ($score >= 70) return 'Avancé';
        if ($score >= 50) return 'Intermédiaire';
        return 'Débutant';
    }

    private function getRecommendation(int $score): string
    {
        if ($score >= 80) {
            return 'Vous avez d\'excellentes compétences comportementales.';
        } elseif ($score >= 60) {
            return 'Bonnes bases comportementales. Travaillez la communication.';
        } else {
            return 'Besoin de développer vos compétences relationnelles.';
        }
    }

    private function detecterPointsForts(string $texte, int $mots, int $phrases): array
    {
        $points = [];

        if ($mots > 80) {
            $points[] = 'Réponse bien développée';
        }

        if ($phrases > 3) {
            $points[] = 'Structure claire avec plusieurs idées';
        }

        if (empty($points)) {
            $points[] = 'Réponse concise et directe';
        }

        return array_slice($points, 0, 2);
    }

    private function genererSuggestions(int $mots, int $phrases): array
    {
        $suggestions = [];

        if ($mots < 50) {
            $suggestions[] = 'Développez davantage vos réponses (50+ mots recommandé)';
        }

        if ($phrases < 2) {
            $suggestions[] = 'Structurez mieux en plusieurs phrases';
        }

        if (empty($suggestions)) {
            $suggestions[] = 'Continuez sur cette bonne voie !';
        }

        return $suggestions;
    }
}
