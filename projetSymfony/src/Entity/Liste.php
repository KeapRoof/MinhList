<?php

namespace App\Entity;

use App\Repository\ListeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ListeRepository::class)]
class Liste
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    private ?string $Nom_Liste = null;

    #[ORM\ManyToOne(inversedBy: 'Listes')]
    private ?User $Id_user = null;

    #[ORM\OneToMany(targetEntity: Contient::class, mappedBy: 'Dans')]
    private Collection $Contenue;

    public function __construct()
    {
        $this->Contenue = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomListe(): ?string
    {
        return $this->Nom_Liste;
    }

    public function setNomListe(string $Nom_Liste): static
    {
        $this->Nom_Liste = $Nom_Liste;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->Id_user;
    }

    public function setIdUser(?User $Id_user): static
    {
        $this->Id_user = $Id_user;

        return $this;
    }

    /**
     * @return Collection<int, Contient>
     */
    public function getContenue(): Collection
    {
        return $this->Contenue;
    }

    public function addContenue(Contient $contenue): static
    {
        if (!$this->Contenue->contains($contenue)) {
            $this->Contenue->add($contenue);
            $contenue->setDans($this);
        }

        return $this;
    }

    public function removeContenue(Contient $contenue): static
    {
        if ($this->Contenue->removeElement($contenue)) {
            // set the owning side to null (unless already changed)
            if ($contenue->getDans() === $this) {
                $contenue->setDans(null);
            }
        }

        return $this;
    }
}
