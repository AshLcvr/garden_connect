<?php

namespace App\DataFixtures;

use App\Entity\Annonce;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AnnonceFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $annonce = new Annonce();
        $annonce->setTitle('Pommes');
        $annonce->setDescription('Goûtez les pommes de notre verger !');
        $annonce->setPrice(2);
        $annonce->setCreatedAt(new DateTimeImmutable());
        $annonce->setActif(true);
        $manager->persist($annonce);

        $annonce2 = new Annonce();
        $annonce2->setTitle('Miel');
        $annonce2->setDescription('Miel de Pont-Audemer !');
        $annonce2->setPrice(5);
        $annonce2->setCreatedAt(new DateTimeImmutable());
        $annonce2->setActif(true);
        $manager->persist($annonce2);

        $manager->flush();
    }
}
