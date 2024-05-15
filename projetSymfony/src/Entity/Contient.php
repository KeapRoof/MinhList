<?php

namespace App\Entity;

use App\Repository\ContientRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContientRepository::class)]
class Contient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\Column]
    private ?bool $Acheter = null;

    #[ORM\ManyToOne(inversedBy: 'Contenue')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Liste $Dans = null;

    #[ORM\ManyToOne(inversedBy: 'contients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Article $Contenue = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function isAcheter(): ?bool
    {
        return $this->Acheter;
    }

    public function setAcheter(bool $Acheter): static
    {
        $this->Acheter = $Acheter;

        return $this;
    }

    public function getDans(): ?Liste
    {
        return $this->Dans;
    }

    public function setDans(?Liste $Dans): static
    {
        $this->Dans = $Dans;

        return $this;
    }

    public function getContenue(): ?Article
    {
        return $this->Contenue;
    }

    public function setContenue(?Article $Contenue): static
    {
        $this->Contenue = $Contenue;

        return $this;
    }
}
