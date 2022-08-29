<?php

namespace App\DataFixtures;

use App\Entity\Avis;
use App\Service\CallApi;
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

    private $callApi;

    public function __construct(CallApi $callApi)
    {
        $this->callApi = $callApi;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; $i++) {
            $avis = (new Avis())
                ->setTitle($this->callApi->generateLipsumusingAsdfast(3,8))
                ->setCommentaire($this->callApi->generateLipsumusingAsdfast(3,15))
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
