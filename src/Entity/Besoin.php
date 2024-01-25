<?php

namespace App\Entity;

use App\Repository\BesoinRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BesoinRepository::class)]
class Besoin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantiteEau = null;

    #[ORM\Column]
    private ?int $frecArrosage = null;

    #[ORM\ManyToOne(inversedBy: 'besoins')]
    private ?Plante $plante = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantiteEau(): ?int
    {
        return $this->quantiteEau;
    }

    public function setQuantiteEau(int $quantiteEau): static
    {
        $this->quantiteEau = $quantiteEau;

        return $this;
    }

    public function getFrecArrosage(): ?int
    {
        return $this->frecArrosage;
    }

    public function setFrecArrosage(int $frecArrosage): static
    {
        $this->frecArrosage = $frecArrosage;

        return $this;
    }

    public function getPlante(): ?Plante
    {
        return $this->plante;
    }

    public function setPlante(?Plante $plante): static
    {
        $this->plante = $plante;

        return $this;
    }
}
