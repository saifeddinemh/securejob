<?php

namespace App\Entity;

use App\Repository\CertificationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CertificationRepository::class)]
class Certification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Badge::class)]
    private ?Badge $badge = null;

    #[ORM\ManyToOne(targetEntity: Candidat::class, inversedBy: "certifications")]
    private ?Candidat $candidat = null;

    #[ORM\Column(length: 50)]
    private ?string $status = "en_attente";

    #[ORM\Column(length: 100)]
    private ?string $issuer = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTime $issuedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $proofUrl = null;

    public function getId(): ?int { return $this->id; }

    public function getBadge(): ?Badge { return $this->badge; }
    public function setBadge(?Badge $badge): static { $this->badge = $badge; return $this; }

    public function getCandidat(): ?Candidat { return $this->candidat; }
    public function setCandidat(?Candidat $candidat): static { $this->candidat = $candidat; return $this; }

    public function getStatus(): ?string { return $this->status; }
    public function setStatus(string $status): static { $this->status = $status; return $this; }

    public function getIssuer(): ?string { return $this->issuer; }
    public function setIssuer(string $issuer): static { $this->issuer = $issuer; return $this; }

    public function getIssuedAt(): ?\DateTime { return $this->issuedAt; }
    public function setIssuedAt(\DateTime $issuedAt): static { $this->issuedAt = $issuedAt; return $this; }

    public function getProofUrl(): ?string { return $this->proofUrl; }
    public function setProofUrl(?string $proofUrl): static { $this->proofUrl = $proofUrl; return $this; }
}
