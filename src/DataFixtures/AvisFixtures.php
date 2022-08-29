<?php

namespace App\DataFixtures;

use App\Entity\Avis;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AvisFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            UserFixtures::class,
            BoutiqueFixtures::class
        ];
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; $i++) {
            $avis = (new Avis())
                ->setTitle('Lorem ipsum dolor sit amet.')
                ->setCommentaire('Ut vero perferendis ut reprehenderit natus eos facere eaque qui dicta perferendis sed laudantium velit ut itaque soluta!')
                ->setRating(random_int(1,5))
                ->setUser($this->getReference('user_'.random_int(1,10)))
                ->setBoutique($this->getReference('boutique_'.random_int(1,10)))
                ->setCreatedAt(new \DateTimeImmutable())
                ->setActif(1);
            $manager->persist($avis);
        }

        $manager->flush();
    }
}
