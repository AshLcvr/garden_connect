<?php

namespace App\Entity;

use App\Repository\BoutiqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    #[Assert\NotBlank(message: 'Veuillez renseigner ce champ')]
    private $title;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\Length(
    max: 1200,
    maxMessage: 'Votre description doit faire au maxmium {{ limit }} caractères de long',
    )]
    private $description;

    #[ORM\Column(type: 'string' , length: 255, nullable: true)]
    #[Assert\Length(
    min: 9,
    max: 12,
    minMessage: ' {{ limit }} chiffres minimum !',
    maxMessage: '{{ limit }} chiffres maximum ! ',
    )]
    #[Assert\Type(
    type: 'numeric',
    message: 'Mauvais format',
    )]
    private $telephone;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $adress;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Veuillez renseigner ce champ')]
    private $city;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank(message: 'Veuillez renseigner ce champ')]
    private $postcode;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank(message: 'Veuillez renseigner ce champ')]
    private $citycode;

    #[ORM\Column(type: 'array', nullable: true)]
    private $coordinates = [];

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

    #[ORM\OneToMany(mappedBy: 'boutique', targetEntity: Avis::class)]
    private $avis;

    #[ORM\OneToMany(mappedBy: 'boutique', targetEntity: Favory::class)]
    private $favories;

    #[ORM\Column(type: 'boolean')]
    private $card_active;

    public function __construct()
    {
        $this->imagesBoutiques = new ArrayCollection();
        $this->annonces = new ArrayCollection();
        $this->avis = new ArrayCollection();
        $this->user = new ArrayCollection();
        $this->favories = new ArrayCollection();
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

    public function getTelephone()
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }


    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(?string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCoordinates(): ?array
    {
        return $this->coordinates;
    }

    public function setCoordinates(?array $coordinates): self
    {
        $this->coordinates = $coordinates;

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

    /**
     * @return Collection<int, Avis>
     */
    public function getAvis(): Collection
    {
        return $this->avis;
    }

    public function addAvis(Avis $avi): self
    {
        if (!$this->avis->contains($avi)) {
            $this->avis[] = $avi;
            $avi->setBoutique($this);
        }
        return $this;
    }

    public function removeAvis(Avis $avi): self
    {
        if ($this->avis->removeElement($avi)) {
            // set the owning side to null (unless already changed)
            if ($avi->getBoutique() === $this) {
                $avi->setBoutique(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, Favory>
     */
    public function getFavories(): Collection
    {
        return $this->favories;
    }

    public function addFavory(Favory $favory): self
    {
        if (!$this->favories->contains($favory)) {
            $this->favories[] = $favory;
            $favory->setBoutique($this);
        }

        return $this;
    }

    public function removeFavory(Favory $favory): self
    {
        if ($this->favories->removeElement($favory)) {
            // set the owning side to null (unless already changed)
            if ($favory->getBoutique() === $this) {
                $favory->setBoutique(null);
            }
        }

        return $this;
    }

    public function isCardActive(): ?bool
    {
        return $this->card_active;
    }

    public function setCardActive(bool $card_active): self
    {
        $this->card_active = $card_active;

        return $this;
    }

    public function getCitycode(): ?string
    {
        return $this->citycode;
    }

    public function setCitycode(?string $citycode): self
    {
        $this->citycode = $citycode;

        return $this;
    }

    public function getPostcode(): ?int
    {
        return $this->postcode;
    }

    public function setPostcode(int $postcode): self
    {
        $this->postcode = $postcode;

        return $this;
    }
}
