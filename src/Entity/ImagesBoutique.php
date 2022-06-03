<?php

namespace App\Entity;

use App\Repository\ImagesBoutiqueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImagesBoutiqueRepository::class)]
class ImagesBoutique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\ManyToOne(targetEntity: Boutique::class, inversedBy: 'imagesBoutiques')]
    private $boutique;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setBoutique(?Boutique $boutique): self
    {
        $this->boutique = $boutique;

        return $this;
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
}
