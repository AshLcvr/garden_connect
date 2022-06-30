<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Il y a déja un compte avec cet e-mail')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\NotBlank(message: 'Veuillez renseigner un E-mail')]
    #[Assert\Email(message : 'Format d\'email invalide')]
    #[Assert\Length(min: 10 ,max: 180, minMessage: 'Votre E-mail doit comporter au moins {{ limit }} caractères', maxMessage: "Votre E-mail ne doit pas exceder {{ limit }} caractères")]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Veuillez renseigner votre Prénom')]
    #[Assert\Length(min: 3 ,max: 150, minMessage: 'Votre Prénom doit comporter au moin {{ limit }} caractères', maxMessage: "Votre Prénom ne pas exceder {{ limit }} caractères")]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Veuillez renseigner votre Nom')]
    #[Assert\Length(min: 3 ,max: 150, minMessage: 'Votre Nom doit comporter au moin {{ limit }} caractères', maxMessage: "Votre Nom ne pas exceder {{ limit }} caractères")]
    private $surname;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $image;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Boutique::class)]
    private $boutiques;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $token;

    #[ORM\Column(type: 'boolean')]
    private $actif;

    #[ORM\Column(type: 'datetime_immutable')]
    private $created_at;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Conversation::class)]
    private $conversations_init;

    #[ORM\OneToMany(mappedBy: 'correspondant', targetEntity: Conversation::class)]
    private $conversations_corresp;

    #[ORM\OneToMany(mappedBy: 'expediteur', targetEntity: Message::class)]
    private $messages;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Avis::class)]
    private $avis;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Favory::class)]
    private $favories;

    public function __construct()
    {
        $this->boutiques = new ArrayCollection();
        $this->conversations_init = new ArrayCollection();
        $this->conversations_corresp = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->avis = new ArrayCollection();
        $this->favories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        // $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string|null $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function isIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    /**
     * @return Collection<int, Boutique>
     */
    public function getBoutiques(): Collection
    {
        return $this->boutiques;
    }

    public function addBoutique(Boutique $boutique): self
    {
        if (!$this->boutiques->contains($boutique)) {
            $this->boutiques[] = $boutique;
            $boutique->setUser($this);
        }

        return $this;
    }

    public function removeBoutique(Boutique $boutique): self
    {
        if ($this->boutiques->removeElement($boutique)) {
            // set the owning side to null (unless already changed)
            if ($boutique->getUser() === $this) {
                $boutique->setUser(null);
            }
        }

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return Collection<int, Conversation>
     */
    public function getConversationsInit(): Collection
    {
        return $this->conversations_init;
    }

    public function addConversationsInit(Conversation $conversationsInit): self
    {
        if (!$this->conversations_init->contains($conversationsInit)) {
            $this->conversations_init[] = $conversationsInit;
            $conversationsInit->setUser($this);
        }

        return $this;
    }

    public function removeConversationsInit(Conversation $conversationsInit): self
    {
        if ($this->conversations_init->removeElement($conversationsInit)) {
            // set the owning side to null (unless already changed)
            if ($conversationsInit->getUser() === $this) {
                $conversationsInit->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Conversation>
     */
    public function getConversationsCorresp(): Collection
    {
        return $this->conversations_corresp;
    }

    public function addConversationsCorresp(Conversation $conversationsCorresp): self
    {
        if (!$this->conversations_corresp->contains($conversationsCorresp)) {
            $this->conversations_corresp[] = $conversationsCorresp;
            $conversationsCorresp->setCorrespondant($this);
        }

        return $this;
    }

    public function removeConversationsCorresp(Conversation $conversationsCorresp): self
    {
        if ($this->conversations_corresp->removeElement($conversationsCorresp)) {
            // set the owning side to null (unless already changed)
            if ($conversationsCorresp->getCorrespondant() === $this) {
                $conversationsCorresp->setCorrespondant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setExpediteur($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getExpediteur() === $this) {
                $message->setExpediteur(null);
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
            $avi->setUser($this);
        }
    }

    public function removeAvis(Avis $avi): self
    {
        if ($this->avis->removeElement($avi)) {
            // set the owning side to null (unless already changed)
            if ($avi->getUser() === $this) {
                $avi->setUser(null);
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
            $favory->setUser($this);
        }

        return $this;
    }

    public function removeFavory(Favory $favory): self
    {
        if ($this->favories->removeElement($favory)) {
            // set the owning side to null (unless already changed)
            if ($favory->getUser() === $this) {
                $favory->setUser(null);
            }
        }

        return $this;
    }
}
