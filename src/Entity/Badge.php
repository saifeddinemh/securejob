<?php

namespace App\Entity;

use App\Repository\BadgeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Badge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private $id;

    #[ORM\Column(type:"string", length:255)]
    private $nom;

    #[ORM\Column(type:"string", length:255, nullable:true)]
    private $description;

    #[ORM\ManyToMany(targetEntity:Candidat::class, mappedBy:"badges")]
    private $candidats;

    public function __construct() {
        $this->candidats = new ArrayCollection();
    }

    // getters et setters
}
