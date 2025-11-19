<?php

namespace App\Entity;

#[ORM\Entity]
class Education
{
#[ORM\Id]
#[ORM\GeneratedValue]
#[ORM\Column]
private ?int $id = null;

#[ORM\ManyToOne(targetEntity: Candidate::class, inversedBy: 'educations')]
private ?Candidate $candidate = null;

#[ORM\Column(length: 255)]
private ?string $degree = null;

#[ORM\Column(length: 255)]
private ?string $school = null;

#[ORM\Column(length: 100)]
private ?string $field = null;

#[ORM\Column(type: 'date')]
private ?\DateTime $startDate = null;

#[ORM\Column(type: 'date', nullable: true)]
private ?\DateTime $endDate = null;

#[ORM\Column(length: 100)]
private ?string $filed = null;

public function getFiled(): ?string
{
    return $this->filed;
}

public function setFiled(string $filed): static
{
    $this->filed = $filed;

    return $this;
}
}
