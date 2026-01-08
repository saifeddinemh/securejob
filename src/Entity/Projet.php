<?php

namespace App\Entity;

use App\Repository\ProjetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjetRepository::class)]
class Projet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $objectifs = null;

    #[ORM\Column]
    private ?int $nombreParticipantsMax = null;

    #[ORM\Column(length: 255)]
    private ?string $statut = 'ouvert'; // ouvert, en cours, termine

    #[ORM\ManyToOne(inversedBy: 'projets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Entreprise $entreprise = null;

    #[ORM\ManyToMany(targetEntity: Candidat::class)]
    private Collection $participants;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): self { $this->nom = $nom; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(string $description): self { $this->description = $description; return $this; }
    public function getObjectifs(): ?string { return $this->objectifs; }
    public function setObjectifs(string $objectifs): self { $this->objectifs = $objectifs; return $this; }
    public function getNombreParticipantsMax(): ?int { return $this->nombreParticipantsMax; }
    public function setNombreParticipantsMax(int $max): self { $this->nombreParticipantsMax = $max; return $this; }
    public function getStatut(): ?string { return $this->statut; }
    public function setStatut(string $statut): self { $this->statut = $statut; return $this; }
    public function getEntreprise(): ?Entreprise { return $this->entreprise; }
    public function setEntreprise(?Entreprise $entreprise): self { $this->entreprise = $entreprise; return $this; }
    public function getParticipants(): Collection { return $this->participants; }
    public function addParticipant(Candidat $participant): self { if (!$this->participants->contains($participant)) { $this->participants->add($participant); } return $this; }
    public function removeParticipant(Candidat $participant): self { $this->participants->removeElement($participant); return $this; }
}
