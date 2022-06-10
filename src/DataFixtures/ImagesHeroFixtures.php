<?php

namespace App\DataFixtures;

use App\Entity\ImagesHero;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ImagesHeroFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $image1 = new ImagesHero();
        $image1->setTitle('vegetables-g3ecd412d0-1920-62a3139111e69.jpg');
        $image2 = new ImagesHero();
        $image2->setTitle('vegetables-g35e990ff8-1920-62a314f9eaadd.jpg');
        $image3 = new ImagesHero();
        $image3->setTitle('honey-g1f7448491-1920-62a315899516e.jpg');
        $image4 = new ImagesHero();
        $image4->setTitle('earth-g855f5e2f7-1280-62a3174633c5e.jpg');
        $manager->persist($image1);
        $manager->persist($image2);
        $manager->persist($image3);
        $manager->persist($image4);
        $manager->flush();
    }
}
