<?php

namespace App\Entity;

use App\Repository\ImagesHeroRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: ImagesHeroRepository::class)]
class ImagesHero
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Gedmo\SortableGroup]
    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[Gedmo\SortablePosition]
    #[ORM\Column(type: 'integer')]
    private $position;

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

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }
}
