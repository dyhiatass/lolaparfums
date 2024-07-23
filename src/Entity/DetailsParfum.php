<?php

namespace App\Entity;

use App\Repository\DetailsParfumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: DetailsParfumRepository::class)]
class DetailsParfum
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $prix = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantite = null;

    #[ORM\Column(length: 255, nullable: true)]
       private ?string $taille = null;

    #[ORM\Column]
    private ?bool $promotion = null;

    #[ORM\Column(nullable: true)]
    private ?float $pourcentagePromotion = null;

    

    #[ORM\ManyToOne(inversedBy: 'detailsParfums')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Parfums $parfums = null;

    #[ORM\OneToMany(mappedBy: 'detailsParfum', targetEntity: PanierItemParfum::class, cascade: ['persist', 'remove'])]
    private Collection $panierItemsParfums;

    public function __construct()
    {
        $this->panierItemsParfums = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getNom(): ?string
    {
        return $this->parfums ? $this->parfums->getNom() : null;
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

    public function getTaille(): ?string
    {
        return $this->taille;
    }

    public function setTaille(string $taille): static
    {
        $this->taille = $taille;
        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): static
    {
        $this->prix = $prix;
        return $this;
    }

    public function getParfums(): ?Parfums
    {
        return $this->parfums;
    }

    public function setParfums(?Parfums $parfums): static
    {
        $this->parfums = $parfums;
        return $this;
    }

    public function isPromotion(): ?bool
    {
        return $this->promotion;
    }

    public function setPromotion(bool $promotion): static
    {
        $this->promotion = $promotion;
        return $this;
    }

    public function getPourcentagePromotion(): ?float
    {
        return $this->pourcentagePromotion;
    }

    public function setPourcentagePromotion(?float $pourcentagePromotion): static
    {
        $this->pourcentagePromotion = $pourcentagePromotion;
        return $this;
    }

    

    /**
     * @return Collection<int, PanierItemParfum>
     */
    public function getPanierItemsParfums(): Collection
    {
        return $this->panierItemsParfums;
    }

    public function addPanierItemsParfum(PanierItemParfum $panierItemsParfum): static
    {
        if (!$this->panierItemsParfums->contains($panierItemsParfum)) {
            $this->panierItemsParfums[] = $panierItemsParfum;
            $panierItemsParfum->setDetailsParfum($this);
        }

        return $this;
    }

    public function removePanierItemsParfum(PanierItemParfum $panierItemsParfum): static
    {
        if ($this->panierItemsParfums->removeElement($panierItemsParfum)) {
            
            if ($panierItemsParfum->getDetailsParfum() === $this) {
                $panierItemsParfum->setDetailsParfum(null);
            }
        }

        return $this;
    }
    public function getTailleChoices(): array
    {
        return [
            '200ml' => '200ml',
            '100ml' => '100ml',
            '75ml' => '75ml',
            '50ml' => '50ml',
            '35ml' => '35ml',
            
        ];
    }

   

    
}
