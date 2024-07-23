<?php

namespace App\Entity;

use App\Repository\ParfumsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParfumsRepository::class)]
class Parfums
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $marque = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(length: 255)]
    private ?string $genre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $concentration = null;

    #[ORM\Column]
    private ?bool $isCoffret = null;

    #[ORM\OneToMany(mappedBy: 'parfums', targetEntity: DetailsParfum::class, cascade: ['persist', 'remove'])]
    private Collection $detailsParfums;

    public function __construct()
    {
        $this->detailsParfums = new ArrayCollection();
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

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): static
    {
        $this->marque = $marque;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;
        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): static
    {
        $this->genre = $genre;
        return $this;
    }

    public function getConcentration(): ?string
    {
        return $this->concentration;
    }

    public function setConcentration(?string $concentration): static
    {
        $this->concentration = $concentration;
        return $this;
    }

    public function isCoffret(): ?bool
    {
        return $this->isCoffret;
    }

    public function setCoffret(bool $isCoffret): static
    {
        $this->isCoffret = $isCoffret;
        return $this;
    }

    /**
     * @return Collection<int, DetailsParfum>
     */
    public function getDetailsParfums(): Collection
    {
        return $this->detailsParfums;
    }

    public function addDetailsParfum(DetailsParfum $detailsParfum): static
    {
        if (!$this->detailsParfums->contains($detailsParfum)) {
            $this->detailsParfums[] = $detailsParfum;
            $detailsParfum->setParfums($this);
        }

        return $this;
    }

    public function removeDetailsParfum(DetailsParfum $detailsParfum): static
    {
        if ($this->detailsParfums->removeElement($detailsParfum)) {
            // set the owning side to null (unless already changed)
            if ($detailsParfum->getParfums() === $this) {
                $detailsParfum->setParfums(null);
            }
        }

        return $this;
    }
    #[ORM\Column(type: 'boolean')]
    private bool $tendance = false;

    #[ORM\Column(type: 'boolean')]
    private bool $coupDeCoeur = false;

   

    public function isTendance(): ?bool
    {
        return $this->tendance;
    }

    public function setTendance(bool $tendance): static
    {
        $this->tendance = $tendance;

        return $this;
    }

    public function isCoupDeCoeur(): ?bool
    {
        return $this->coupDeCoeur;
    }

    public function setCoupDeCoeur(bool $coupDeCoeur): static
    {
        $this->coupDeCoeur = $coupDeCoeur;

        return $this;
    }
    #[ORM\Column]
    private ?bool $meilleursVente = null;
    public function isMeilleursVente(): ?bool
    {
        return $this->meilleursVente;
    }

    public function setMeilleursVente(bool $meilleursVente): static
    {
        $this->meilleursVente = $meilleursVente;
        return $this;
    }
    public function getPrix(): array
{
    $prix = [];
    foreach ($this->detailsParfums as $details) {
        $prix[] = $details->getPrix();
    }
    return $prix;
}

}
