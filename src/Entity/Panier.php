<?php
namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateCreation = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'paniers', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * @var Collection<int, PanierItemParfum>
     */
    #[ORM\OneToMany(targetEntity: PanierItemParfum::class, mappedBy: 'panier')]
    private Collection $panierItemParfums;

    public function __construct()
    {
        $this->panierItemParfums = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCreation(): ?\DateTimeImmutable
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeImmutable $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

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
     * @return Collection<int, PanierItemParfum>
     */
    public function getPanierItemParfums(): Collection
    {
        return $this->panierItemParfums;
    }

    public function addPanierItemParfum(PanierItemParfum $panierItemParfum): static
    {
        if (!$this->panierItemParfums->contains($panierItemParfum)) {
            $this->panierItemParfums->add($panierItemParfum);
            $panierItemParfum->setPanier($this);
        }

        return $this;
    }

    public function removePanierItemParfum(PanierItemParfum $panierItemParfum): static
    {
        if ($this->panierItemParfums->removeElement($panierItemParfum)) {
            // set the owning side to null (unless already changed)
            if ($panierItemParfum->getPanier() === $this) {
                $panierItemParfum->setPanier(null);
            }
        }

        return $this;
    }
}
