<?php

namespace App\Entity;

use App\Repository\AnnonceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AnnonceRepository::class)]
class Annonce
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Veuillez renseigner un titre')]
    #[Assert\Length(
    min: 3,
    max: 255,
    minMessage: 'Votre titre doit faire au moins {{ limit }} caractères de long',
    maxMessage: 'Votre titre doit faire au maxmium {{ limit }} caractères de long',
    )]
    private $title;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\Length(
    max: 1200,
    maxMessage: 'Votre description doit faire au maxmium {{ limit }} caractères de long',
    )]
    private $description;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank(message: 'Veuillez renseigner un prix')]
    #[Assert\Type(
    type: 'integer',
    message: 'Le prix doit être un nombre.',
    )]
    private $price;

    #[ORM\Column(type: 'datetime_immutable')]
    private $created_at;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $modified_at;

    #[ORM\Column(type: 'boolean')]
    private $actif;

    #[ORM\ManyToOne(targetEntity: Boutique::class, inversedBy: 'annonces')]
    #[ORM\JoinColumn(nullable: false)]
    private $boutique;

    #[ORM\OneToMany(mappedBy: 'annonce', targetEntity: ImagesAnnonces::class)]
    private $imagesAnnonces;

    #[ORM\ManyToOne(targetEntity: Subcategory::class, inversedBy: 'annonces')]
    #[ORM\JoinColumn(nullable: false)]
    private $subcategory;

    #[ORM\ManyToOne(targetEntity: Mesure::class, inversedBy: 'annonces')]
    #[ORM\JoinColumn(nullable: false)]
    private $mesure;



    public function __construct()
    {
        $this->imagesAnnonces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getModifiedAt(): ?\DateTimeImmutable
    {
        return $this->modified_at;
    }

    public function setModifiedAt(?\DateTimeImmutable $modified_at): self
    {
        $this->modified_at = $modified_at;

        return $this;
    }

    public function isActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getBoutique(): ?Boutique
    {
        return $this->boutique;
    }

    public function setBoutique(?Boutique $boutique): self
    {
        $this->boutique = $boutique;

        return $this;
    }

    /**
     * @return Collection<int, ImagesAnnonces>
     */
    public function getImagesAnnonces(): Collection
    {
        return $this->imagesAnnonces;
    }

    public function addImagesAnnonce(ImagesAnnonces $imagesAnnonce): self
    {
        if (!$this->imagesAnnonces->contains($imagesAnnonce)) {
            $this->imagesAnnonces[] = $imagesAnnonce;
            $imagesAnnonce->setAnnonce($this);
        }

        return $this;
    }

    public function removeImagesAnnonce(ImagesAnnonces $imagesAnnonce): self
    {
        if ($this->imagesAnnonces->removeElement($imagesAnnonce)) {
            // set the owning side to null (unless already changed)
            if ($imagesAnnonce->getAnnonce() === $this) {
                $imagesAnnonce->setAnnonce(null);
            }
        }

        return $this;
    }

    public function getSubcategory(): ?Subcategory
    {
        return $this->subcategory;
    }

    public function setSubcategory(?Subcategory $subcategory): self
    {
        $this->subcategory = $subcategory;

        return $this;
    }

    public function getMesure(): ?Mesure
    {
        return $this->mesure;
    }

    public function setMesure(?Mesure $mesure): self
    {
        $this->mesure = $mesure;

        return $this;
    }

}
