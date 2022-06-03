<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoriesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $legumes = new Categories();
        $legumes->setTitle('Légumes');
        $manager->persist($legumes);

        $haricots = new Categories();
        $haricots->setTitle('Haricots');
        $haricots->setParent($legumes);
        $manager->persist($haricots);

        $endives = new Categories();
        $endives->setTitle('Endives');
        $endives->setParent($legumes);
        $manager->persist($endives);

        $fruits = new Categories();
        $fruits->setTitle('Fruits');
        $manager->persist($fruits);

        $fraises = new Categories();
        $fraises->setTitle('Fraises');
        $fraises->setParent($fruits);
        $manager->persist($fraises);

        $bananes = new Categories();
        $bananes->setTitle('Bananes');
        $bananes->setParent($fruits);
        $manager->persist($bananes);

        $manager->flush();
    }
}
