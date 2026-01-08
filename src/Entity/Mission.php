<?php

namespace App\Entity;

use App\Repository\MissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MissionRepository::class)]
class Mission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $duree = null;

    #[ORM\Column]
    private ?float $budget = null;

    #[ORM\Column(length: 255)]
    private ?string $statut = 'ouverte'; // ouverte, en cours, terminee

    #[ORM\ManyToOne(inversedBy: 'missions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Entreprise $entreprise = null;

    #[ORM\ManyToMany(targetEntity: Competence::class)]
    private Collection $competencesRequises;

    #[ORM\OneToMany(targetEntity: Candidature::class, mappedBy: 'mission')]
    private Collection $candidatures;

    #[ORM\ManyToOne(targetEntity: Candidat::class)]
    private ?Candidat $candidatAssigne = null;

    public function __construct()
    {
        $this->competencesRequises = new ArrayCollection();
        $this->candidatures = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getTitre(): ?string { return $this->titre; }
    public function setTitre(string $titre): self { $this->titre = $titre; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(string $description): self { $this->description = $description; return $this; }
    public function getDuree(): ?string { return $this->duree; }
    public function setDuree(string $duree): self { $this->duree = $duree; return $this; }
    public function getBudget(): ?float { return $this->budget; }
    public function setBudget(float $budget): self { $this->budget = $budget; return $this; }
    public function getStatut(): ?string { return $this->statut; }
    public function setStatut(string $statut): self { $this->statut = $statut; return $this; }
    public function getEntreprise(): ?Entreprise { return $this->entreprise; }
    public function setEntreprise(?Entreprise $entreprise): self { $this->entreprise = $entreprise; return $this; }
    public function getCompetencesRequises(): Collection { return $this->competencesRequises; }
    public function addCompetenceRequise(Competence $competence): self { if (!$this->competencesRequises->contains($competence)) { $this->competencesRequises->add($competence); } return $this; }
    public function removeCompetenceRequise(Competence $competence): self { $this->competencesRequises->removeElement($competence); return $this; }
    public function getCandidatures(): Collection { return $this->candidatures; }
    public function getCandidatAssigne(): ?Candidat { return $this->candidatAssigne; }
    public function setCandidatAssigne(?Candidat $candidatAssigne): self { $this->candidatAssigne = $candidatAssigne; return $this; }
}
