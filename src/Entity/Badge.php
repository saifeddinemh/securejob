<?php

namespace App\Entity;
#[ORM\Entity]
class Badge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Candidate::class, inversedBy: 'badges')]
    private ?Candidate $candidate = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $issuer = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTime $issueDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $certificateUrl = null;
}
