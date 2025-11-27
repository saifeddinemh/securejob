<?php

namespace App\Entity;

use App\Repository\ExperienceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Candidat;

#[ORM\Entity(repositoryClass: ExperienceRepository::class)]
class Experience
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private $id;

    #[ORM\Column(type:"string", length:255)]
    private $poste;

    #[ORM\Column(type:"string", length:255)]
    private $entreprise;

    #[ORM\Column(type:"date", nullable:true)] // nullable pour éviter erreur
    private $dateDebut;

    #[ORM\Column(type:"date", nullable:true)]
    private $dateFin;

    #[ORM\Column(type:"text", nullable:true)]
    private $description;

    #[ORM\ManyToOne(targetEntity:Candidat::class, inversedBy:"experiences")]
    #[ORM\JoinColumn(nullable:false)]
    private $candidat;

    public function __construct()
    {
        // dates par défaut pour éviter erreur SQL si non renseignées
        $this->dateDebut = new \DateTime('2000-01-01');
        $this->dateFin = null;
    }

    // ----- Getters & Setters -----

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPoste(): ?string
    {
        return $this->poste;
    }

    public function setPoste(string $poste): static
    {
        $this->poste = $poste;
        return $this;
    }

    public function getEntreprise(): ?string
    {
        return $this->entreprise;
    }

    public function setEntreprise(string $entreprise): static
    {
        $this->entreprise = $entreprise;
        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;
        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getCandidat(): ?Candidat
    {
        return $this->candidat;
    }

    public function setCandidat(?Candidat $candidat): static
    {
        $this->candidat = $candidat;
        return $this;
    }
}
