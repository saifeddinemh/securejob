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
    #[ORM\Column(type:"integer")]
    private $id;

    #[ORM\Column(type:"string", length:255)]
    private $poste;

    #[ORM\Column(type:"string", length:255)]
    private $entreprise;

    #[ORM\Column(type:"date")]
    private $dateDebut;

    #[ORM\Column(type:"date", nullable:true)]
    private $dateFin;

    #[ORM\Column(type:"text", nullable:true)]
    private $description;

    #[ORM\ManyToOne(targetEntity:Candidat::class, inversedBy:"experiences")]
    #[ORM\JoinColumn(nullable:false)]
    private $candidat;

    // getters et setters
}
