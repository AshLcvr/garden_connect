<?php

namespace App\DataFixtures;

use App\Entity\Avis;
use App\Repository\BoutiqueRepository;
use App\Repository\UserRepository;
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
    private $boutiqueRepository;
    private $userRepository;

    public function __construct(CallApi $callApi, BoutiqueRepository $boutiqueRepository, UserRepository $userRepository)
    {
        $this->callApi            = $callApi;
        $this->boutiqueRepository = $boutiqueRepository;
        $this->userRepository     = $userRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $allBoutiques  = $this->boutiqueRepository->findAll();
        $allUsers      = $this->userRepository->findAll();
        foreach ($allBoutiques as $boutique) {
            $boutiqueCreationDate = $boutique->getCreatedAt();
            $avisDate             = $boutiqueCreationDate->modify(random_int(1,5) . 'days');
            $userAvisArray = [];
            for ($i = 0; $i <= random_int(2, 5); $i++) {
                $randomUser      = $allUsers[random_int(0, count($allUsers)-1)];
                $userAvisArray[] = $randomUser->getId();
                if ($randomUser !== $boutique->getUser() || !in_array($randomUser->getId(),$userAvisArray)){
                    $avis = (new Avis())
                        ->setTitle($this->callApi->generateLipsumusingAsdfast(3, 8))
                        ->setCommentaire($this->callApi->generateLipsumusingAsdfast(3, 15))
                        ->setRating(random_int(2, 5))
                        ->setUser($randomUser)
                        ->setBoutique($boutique)
                        ->setCreatedAt($avisDate)
                        ->setActif(1);
                    $manager->persist($avis);
                }
            }
        }

        $manager->flush();
    }
}
