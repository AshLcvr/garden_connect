<?php

namespace App\Entity;

use App\Repository\BoutiqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BoutiqueRepository::class)]
class Boutique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
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

    #[ORM\Column(type: 'string' , length: 255, nullable: true)]
    #[Assert\Length(
    min: 10,
    max: 13,
    minMessage: ' {{ limit }} chiffres minimum !',
    maxMessage: '{{ limit }} chiffres maximum ! ',
    )]
    #[Assert\Type(
    type: 'numeric',
    message: 'Mauvais format',
    )]
    private $telephone;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $adresse;

    #[ORM\Column(type: 'string' , length: 255, nullable: true)]
    #[Assert\Length(
    min: 5,
    max: 5,
    exactMessage: 'Votre Code Postal doit comporter 5 chiffres !',
    )]
    #[Assert\Type(
    type: 'integer',
    message: 'Le code postal ne correspond pas.',
    )]
    private $code_postal;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $city;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Gedmo\Timestampable(on: 'create')]
    private $created_at;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $modified_at;

    #[ORM\Column(type: 'boolean')]
    private $actif;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'boutiques')]
    private $user;

    #[ORM\OneToMany(mappedBy: 'boutique', targetEntity: ImagesBoutique::class)]
    private $imagesBoutiques;

    #[ORM\OneToMany(mappedBy: 'boutique', targetEntity: Annonce::class)]
    private $annonces;

    public function __construct()
    {
        $this->imagesBoutiques = new ArrayCollection();
        $this->annonces = new ArrayCollection();
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

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(int|string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->code_postal;
    }

    public function setCodePostal(int $code_postal): self
    {
        $this->code_postal = $code_postal;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, ImagesBoutique>
     */
    public function getImagesBoutiques(): Collection
    {
        return $this->imagesBoutiques;
    }

    public function addImagesBoutique(ImagesBoutique $imagesBoutique): self
    {
        if (!$this->imagesBoutiques->contains($imagesBoutique)) {
            $this->imagesBoutiques[] = $imagesBoutique;
            $imagesBoutique->setBoutique($this);
        }

        return $this;
    }

    public function removeImagesBoutique(ImagesBoutique $imagesBoutique): self
    {
        if ($this->imagesBoutiques->removeElement($imagesBoutique)) {
            // set the owning side to null (unless already changed)
            if ($imagesBoutique->getBoutique() === $this) {
                $imagesBoutique->setBoutique(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Annonce>
     */
    public function getAnnonces(): Collection
    {
        return $this->annonces;
    }

    public function addAnnonce(Annonce $annonce): self
    {
        if (!$this->annonces->contains($annonce)) {
            $this->annonces[] = $annonce;
            $annonce->setBoutique($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonce $annonce): self
    {
        if ($this->annonces->removeElement($annonce)) {
            // set the owning side to null (unless already changed)
            if ($annonce->getBoutique() === $this) {
                $annonce->setBoutique(null);
            }
        }

        return $this;
    }
}
