<?php

namespace App\Entity;

use App\Repository\ProduitsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitsRepository::class)]
class Produits
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_du_produit = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $prix = null;

    /**
     * @var Collection<int, taille>
     */
    #[ORM\ManyToMany(targetEntity: Taille::class, inversedBy: 'produits')]
    private Collection $taille;

    /**
     * @var Collection<int, couleur>
     */
    #[ORM\ManyToMany(targetEntity: Couleur::class, inversedBy: 'produits')]
    private Collection $couleur;

    /**
     * @var Collection<int, Images>
     */
    #[ORM\OneToMany(targetEntity: Images::class, mappedBy: 'produit', cascade: ['persist', 'remove'])]
    private Collection $images;

    /**
     * @var Collection<int, Avis>
     */
    #[ORM\OneToMany(targetEntity: Avis::class, mappedBy: 'avis')]
    private Collection $avis;

    /**
     * @var Collection<int, CommandeProduit>
     */
    #[ORM\OneToMany(targetEntity: CommandeProduit::class, mappedBy: 'produit')]
    private Collection $commandeProduits;

    public function __construct()
    {
        $this->taille = new ArrayCollection();
        $this->couleur = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->avis = new ArrayCollection();
        $this->commandeProduits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomDuProduit(): ?string
    {
        return $this->nom_du_produit;
    }
    public function __toString(): string
    {
        return $this->nom_du_produit ?? 'Produit inconnu';
    }


    public function setNomDuProduit(string $nom_du_produit): static
    {
        $this->nom_du_produit = $nom_du_produit;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * @return Collection<int, taille>
     */
    public function getTaille(): Collection
    {
        return $this->taille;
    }

    public function addTaille(taille $taille): static
    {
        if (!$this->taille->contains($taille)) {
            $this->taille->add($taille);
        }

        return $this;
    }

    public function removeTaille(taille $taille): static
    {
        $this->taille->removeElement($taille);

        return $this;
    }

    /**
     * @return Collection<int, couleur>
     */
    public function getCouleur(): Collection
    {
        return $this->couleur;
    }

    public function addCouleur(couleur $couleur): static
    {
        if (!$this->couleur->contains($couleur)) {
            $this->couleur->add($couleur);
        }

        return $this;
    }

    public function removeCouleur(couleur $couleur): static
    {
        $this->couleur->removeElement($couleur);

        return $this;
    }

    /**
     * @return Collection<int, Images>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setProduit($this);
        }

        return $this;
    }

    public function removeImage(Images $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getProduit() === $this) {
                $image->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Avis>
     */
    public function getAvis(): Collection
    {
        return $this->avis = new ArrayCollection();
    }

    public function addAvis(Avis $avis): static
    {
        if (!$this->avis->contains($avis)) {
            $this->avis->add($avis);
            $avis->setProduits($this);
        }

        return $this;
    }

    public function removeAvi(Avis $avis): static
    {
        if ($this->avis->removeElement($avis)) {
            // set the owning side to null (unless already changed)
            if ($avis->getProduits() === $this) {
                $avis->setProduits(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CommandeProduit>
     */
    public function getCommandeProduits(): Collection
    {
        return $this->commandeProduits;
    }

    public function addCommandeProduit(CommandeProduit $commandeProduit): static
    {
        if (!$this->commandeProduits->contains($commandeProduit)) {
            $this->commandeProduits->add($commandeProduit);
            $commandeProduit->setProduit($this);
        }

        return $this;
    }

    public function removeCommandeProduit(CommandeProduit $commandeProduit): static
    {
        if ($this->commandeProduits->removeElement($commandeProduit)) {
            // set the owning side to null (unless already changed)
            if ($commandeProduit->getProduit() === $this) {
                $commandeProduit->setProduit(null);
            }
        }

        return $this;
    }
}
