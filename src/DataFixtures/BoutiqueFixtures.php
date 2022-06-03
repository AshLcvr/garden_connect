<?php

namespace App\DataFixtures;

use App\Entity\Boutique;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use function App\Entity\setCreatedAt;

class   BoutiqueFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }


    public function load(ObjectManager $manager): void
    {
        $boutiquePolo = (new Boutique())
            ->setTitle('La boutique de Polo')
            ->setDescription('La boutique qu\'elle est belle')
            ->setUser($this->getReference('polo'))
            ->setActif(1)
            ->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($boutiquePolo);

        $boutiqueSacha= (new Boutique())
            ->setTitle('La boutique de Sacha')
            ->setDescription('La boutique qu\'elle est belle')
            ->setUser($this->getReference('sacha'))
            ->setActif(1)
            ->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($boutiqueSacha);

        $boutiqueOrianne = (new Boutique())
            ->setTitle('La boutique de Orianne')
            ->setDescription('La boutique qu\'elle est belle')
            ->setUser($this->getReference('orianne'))
            ->setActif(1)
            ->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($boutiqueOrianne);

        $manager->flush();

        $this->addReference('boutique_polo', $boutiquePolo);
        $this->addReference('boutique_sacha', $boutiqueSacha);
        $this->addReference('boutique_orianne', $boutiqueOrianne);
    }


}
