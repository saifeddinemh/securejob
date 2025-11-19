<?php

namespace App\Entity;

use App\Repository\ExperienceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExperienceRepository::class)]
class Experience
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $jobTitle = null;

    #[ORM\Column(length: 255)]
    private ?string $company = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column]
    private bool $currentJob = false;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(targetEntity: Candidate::class, inversedBy: 'experiences')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Candidate $candidate = null;

    public function __construct()
    {
        $this->startDate = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJobTitle(): ?string
    {
        return $this->jobTitle;
    }

    public function setJobTitle(string $jobTitle): static
    {
        $this->jobTitle = $jobTitle;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(string $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function isCurrentJob(): bool
    {
        return $this->currentJob;
    }

    public function setCurrentJob(bool $currentJob): static
    {
        $this->currentJob = $currentJob;

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

    public function getCandidate(): ?Candidate
    {
        return $this->candidate;
    }

    public function setCandidate(?Candidate $candidate): static
    {
        $this->candidate = $candidate;

        return $this;
    }

    // Méthode utilitaire pour calculer la durée
    public function getDuration(): string
    {
        $start = $this->startDate;
        $end = $this->currentJob ? new \DateTime() : $this->endDate;

        if (!$end) {
            return 'En cours';
        }

        $interval = $start->diff($end);

        $years = $interval->y;
        $months = $interval->m;

        if ($years > 0 && $months > 0) {
            return $years . ' an' . ($years > 1 ? 's' : '') . ' et ' . $months . ' mois';
        } elseif ($years > 0) {
            return $years . ' an' . ($years > 1 ? 's' : '');
        } else {
            return $months . ' mois';
        }
    }

    public function __toString(): string
    {
        return $this->jobTitle . ' chez ' . $this->company;
    }
}
