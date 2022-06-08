<?php

namespace App\DataFixtures;

use DateTimeImmutable;
use App\Entity\Annonce;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AnnonceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $annonce = new Annonce();
        $annonce->setTitle('Pommes');
        $annonce->setDescription('Goûtez les pommes de notre verger ! Intelligentsia tofu echo park, jean shorts cred typewriter crucifix leggings next level.');
        $annonce->setPrice(2);
        $annonce->setCreatedAt(new DateTimeImmutable());
        $annonce->setActif(true);
        $annonce->setBoutique($this->getReference('boutique_polo'));
        $manager->persist($annonce);

        $annonce2 = new Annonce();
        $annonce2->setTitle('Miel');
        $annonce2->setDescription('Miel de Pont-Audemer ! Intelligentsia tofu echo park, jean shorts cred typewriter crucifix leggings next level.');
        $annonce2->setPrice(5);
        $annonce2->setCreatedAt(new DateTimeImmutable());
        $annonce2->setActif(true);
        $annonce2->setBoutique($this->getReference('boutique_sacha'));
        $manager->persist($annonce2);

        $manager->flush();
    }
    public function getDependencies()
    {
        return [
            BoutiqueFixtures::class
        ];
    }
}
