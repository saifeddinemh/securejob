<?php

namespace App\Entity;

use App\Repository\LangueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Langue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private $id;

    #[ORM\Column(type:"string", length:100)]
    private $nom;

    #[ORM\ManyToMany(targetEntity:Candidat::class, mappedBy:"langues")]
    private $candidats;

    public function __construct() {
        $this->candidats = new ArrayCollection();
    }

    // getters et setters
}
