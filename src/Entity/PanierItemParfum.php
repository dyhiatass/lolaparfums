<?php

namespace App\Entity;

use App\Repository\PanierItemParfumRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PanierItemParfumRepository::class)]
class PanierItemParfum
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $prixTotal = null;

    #[ORM\ManyToOne(inversedBy: 'panierItemsParfums')]
    #[ORM\JoinColumn(nullable: false)]
    private ?DetailsParfum $detailsParfum = null;

    #[ORM\ManyToOne(inversedBy: 'panierItemParfums')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Panier $panier = null;

    #[ORM\Column(length: 255, nullable: true)] 
    private ?string $taille = null;
    public function getTaille(): ?string
    {
        return $this->taille;
    }

    public function setTaille(?string $taille): static
    {
        $this->taille = $taille;
        return $this;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): static
    {
        $this->quantite = $quantite;
        return $this;
    }

    public function getPrixTotal(): ?string
    {
        return $this->prixTotal;
    }


    public function setPrixTotal(string $prixTotal): static
    {
        $this->prixTotal = $prixTotal;
        return $this;
    }

    public function getDetailsParfum(): ?DetailsParfum
    {
        return $this->detailsParfum;
    }

    public function setDetailsParfum(?DetailsParfum $detailsParfum): static
    {
        $this->detailsParfum = $detailsParfum;
        return $this;
    }

    public function getPanier(): ?Panier
    {
        return $this->panier;
    }

    public function setPanier(?Panier $panier): static
    {
        $this->panier = $panier;
        return $this;
    }
}
