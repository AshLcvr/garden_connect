<?php

namespace App\Entity;

use App\Repository\FavoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavoryRepository::class)]
class Favory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'favories')]
    private $user;

    #[ORM\ManyToOne(targetEntity: Boutique::class, inversedBy: 'favories')]
    private $boutique;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getBoutique(): ?Boutique
    {
        return $this->boutique;
    }

    public function setBoutique(?Boutique $boutique): self
    {
        $this->boutique = $boutique;

        return $this;
    }
}
