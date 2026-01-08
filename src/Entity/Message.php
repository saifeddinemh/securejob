<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $contenu = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $auteur = null;

    #[ORM\ManyToOne(targetEntity: Mission::class)]
    private ?Mission $mission = null;

    #[ORM\ManyToOne(targetEntity: Projet::class)]
    private ?Projet $projet = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pieceJointePath = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getContenu(): ?string { return $this->contenu; }
    public function setContenu(string $contenu): self { $this->contenu = $contenu; return $this; }
    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function getAuteur(): ?User { return $this->auteur; }
    public function setAuteur(?User $auteur): self { $this->auteur = $auteur; return $this; }
    public function getMission(): ?Mission { return $this->mission; }
    public function setMission(?Mission $mission): self { $this->mission = $mission; return $this; }
    public function getProjet(): ?Projet { return $this->projet; }
    public function setProjet(?Projet $projet): self { $this->projet = $projet; return $this; }
    public function getPieceJointePath(): ?string { return $this->pieceJointePath; }
    public function setPieceJointePath(?string $path): self { $this->pieceJointePath = $path; return $this; }
}
