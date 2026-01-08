<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
class Candidat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $nom;

    #[ORM\Column(type: 'string', length: 255)]
    private string $prenom;

    #[ORM\Column(type: 'string', length: 255)]
    private string $email;

    #[ORM\Column(type: 'string', length: 20)]
    private string $telephone;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $dateNaissance = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $adresse;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $cvPath = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $photoPath = null;

    #[ORM\OneToMany(targetEntity: Experience::class, mappedBy: 'candidat', cascade: ['persist', 'remove'])]
    private Collection $experiences;

    #[ORM\OneToMany(targetEntity: Formation::class, mappedBy: 'candidat', cascade: ['persist', 'remove'])]
    private Collection $formations;

    #[ORM\ManyToMany(targetEntity: Langue::class)]
    private Collection $langues;

    #[ORM\ManyToMany(targetEntity: Badge::class)]
    private Collection $badges;

    #[ORM\ManyToMany(targetEntity: Competence::class)]
    private Collection $competences;

    #[ORM\ManyToMany(targetEntity: Projet::class, mappedBy: 'participants')]
    private Collection $projets;

    #[ORM\OneToMany(mappedBy: 'candidatAssigne', targetEntity: Mission::class)]
    private Collection $missions;

    public function __construct()
    {
        $this->experiences = new ArrayCollection();
        $this->formations  = new ArrayCollection();
        $this->langues     = new ArrayCollection();
        $this->badges      = new ArrayCollection();
        $this->competences = new ArrayCollection();
        $this->projets = new ArrayCollection();
        $this->missions = new ArrayCollection();
    }

    // -----------------------------------------------------
    // GETTERS & SETTERS
    // -----------------------------------------------------

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;
        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(?\DateTimeInterface $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;
        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getCvPath(): ?string
    {
        return $this->cvPath;
    }

    public function setCvPath(?string $cvPath): self
    {
        $this->cvPath = $cvPath;
        return $this;
    }

    public function getPhotoPath(): ?string
    {
        return $this->photoPath;
    }

    public function setPhotoPath(?string $photoPath): self
    {
        $this->photoPath = $photoPath;
        return $this;
    }

    // -----------------------------------------------------
    // EXPERIENCES
    // -----------------------------------------------------

    public function getExperiences(): Collection
    {
        return $this->experiences;
    }

    public function addExperience(Experience $experience): self
    {
        if (!$this->experiences->contains($experience)) {
            $this->experiences->add($experience);
            $experience->setCandidat($this);
        }

        return $this;
    }

    public function removeExperience(Experience $experience): self
    {
        if ($this->experiences->removeElement($experience)) {
            if ($experience->getCandidat() === $this) {
                $experience->setCandidat(null);
            }
        }
        return $this;
    }

    // -----------------------------------------------------
    // FORMATIONS
    // -----------------------------------------------------

    public function getFormations(): Collection
    {
        return $this->formations;
    }

    public function addFormation(Formation $formation): self
    {
        if (!$this->formations->contains($formation)) {
            $this->formations->add($formation);
            $formation->setCandidat($this);
        }
        return $this;
    }

    public function removeFormation(Formation $formation): self
    {
        if ($this->formations->removeElement($formation)) {
            if ($formation->getCandidat() === $this) {
                $formation->setCandidat(null);
            }
        }
        return $this;
    }

    // -----------------------------------------------------
    // LANGUES
    // -----------------------------------------------------

    public function getLangues(): Collection
    {
        return $this->langues;
    }

    public function addLangue(Langue $langue): self
    {
        if (!$this->langues->contains($langue)) {
            $this->langues->add($langue);
        }
        return $this;
    }

    public function removeLangue(Langue $langue): self
    {
        $this->langues->removeElement($langue);
        return $this;
    }

    // -----------------------------------------------------
    // BADGES
    // -----------------------------------------------------

    public function getBadges(): Collection
    {
        return $this->badges;
    }

    public function addBadge(Badge $badge): self
    {
        if (!$this->badges->contains($badge)) {
            $this->badges->add($badge);
        }
        return $this;
    }

    public function removeBadge(Badge $badge): self
    {
        $this->badges->removeElement($badge);
        return $this;
    }

    // -----------------------------------------------------
    // COMPETENCES
    // -----------------------------------------------------

    public function getCompetences(): Collection
    {
        return $this->competences;
    }

    public function addCompetence(Competence $competence): self
    {
        if (!$this->competences->contains($competence)) {
            $this->competences->add($competence);
        }
        return $this;
    }

    public function removeCompetence(Competence $competence): self
    {
        $this->competences->removeElement($competence);
        return $this;
    }
}
