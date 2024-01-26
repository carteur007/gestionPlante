<?php

namespace App\Entity;

use App\Repository\PlanteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Plante
 * 
 * @author Saatsa franklin Blerio <saatsafranklin@gmail.com>
 * @description Analyste programmeur/developpeur full-strack
 *
 */
#[ORM\Entity(repositoryClass: PlanteRepository::class)]
class Plante
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $especePlante = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateAchat = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imagePlante = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isArrosed = null;

    #[ORM\ManyToOne(inversedBy: 'plantes')]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'plante', targetEntity: Besoin::class)]
    private Collection $besoins;

    #[ORM\OneToMany(mappedBy: 'plante', targetEntity: Historique::class)]
    private Collection $historiques;

    public function __construct()
    {
        $this->besoins = new ArrayCollection();
        $this->historiques = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getEspecePlante(): ?string
    {
        return $this->especePlante;
    }

    public function setEspecePlante(string $especePlante): static
    {
        $this->especePlante = $especePlante;

        return $this;
    }

    public function getDateAchat(): ?\DateTimeInterface
    {
        return $this->dateAchat;
    }

    public function setDateAchat(?\DateTimeInterface $dateAchat): static
    {
        $this->dateAchat = $dateAchat;

        return $this;
    }

    public function getImagePlante(): ?string
    {
        return $this->imagePlante;
    }

    public function setImagePlante(?string $imagePlante): static
    {
        $this->imagePlante = $imagePlante;

        return $this;
    }

    public function isIsArrosed(): ?bool
    {
        return $this->isArrosed;
    }

    public function setIsArrosed(?bool $isArrosed): static
    {
        $this->isArrosed = $isArrosed;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Besoin>
     */
    public function getBesoins(): Collection
    {
        return $this->besoins;
    }

    public function addBesoin(Besoin $besoin): static
    {
        if (!$this->besoins->contains($besoin)) {
            $this->besoins->add($besoin);
            $besoin->setPlante($this);
        }

        return $this;
    }

    public function removeBesoin(Besoin $besoin): static
    {
        if ($this->besoins->removeElement($besoin)) {
            // set the owning side to null (unless already changed)
            if ($besoin->getPlante() === $this) {
                $besoin->setPlante(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Historique>
     */
    public function getHistoriques(): Collection
    {
        return $this->historiques;
    }

    public function addHistorique(Historique $historique): static
    {
        if (!$this->historiques->contains($historique)) {
            $this->historiques->add($historique);
            $historique->setPlante($this);
        }

        return $this;
    }

    public function removeHistorique(Historique $historique): static
    {
        if ($this->historiques->removeElement($historique)) {
            // set the owning side to null (unless already changed)
            if ($historique->getPlante() === $this) {
                $historique->setPlante(null);
            }
        }

        return $this;
    }
}
