<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private $id;

    #[ORM\Column(type:"string", length:255)]
    private $titre;

    #[ORM\Column(type:"string", length:255)]
    private $etablissement;

    #[ORM\Column(type:"date")]
    private $dateDebut;

    #[ORM\Column(type:"date", nullable:true)]
    private $dateFin;

    #[ORM\ManyToOne(targetEntity:Candidat::class, inversedBy:"formations")]
    #[ORM\JoinColumn(nullable:false)]
    private $candidat;

    // getters et setters
}
