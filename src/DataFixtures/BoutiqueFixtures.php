<?php

namespace App\DataFixtures;

use App\Entity\Boutique;
use App\Entity\ImagesBoutique;
use App\Repository\UserRepository;
use App\Service\CallApi;
use Faker;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class   BoutiqueFixtures extends Fixture implements DependentFixtureInterface
{
    private $userRepository;
    private $callApi;

    public function __construct(UserRepository $userRepository, CallApi $callApi)
    {
        $this->userRepository = $userRepository;
        $this->callApi        = $callApi;
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }

    public function load(ObjectManager $manager ): void
    {
        $imageBoutique = (new ImagesBoutique())
            ->setTitle('imageBoutiqueDefault.jpg');
            $manager->persist($imageBoutique);

        $boutiqueTest = (new Boutique())
            ->setTitle('La boutique Test')
            ->setDescription('La boutique test de Garden Connect.')
            ->setCity('Pont-Audemer')
            ->setPostcode(27500)
            ->setCitycode(27467)
            ->setLng(0.525508)
            ->setLat(49.346658)
            ->setTelephone('')
            ->setUser($this->getReference('vendeur_test'))
            ->setActif(1)
            ->setCardActive(1)
            ->addImagesBoutique($imageBoutique)
            ->setCreatedAt(new \DateTimeImmutable('-2 week'));
        $this->addReference('boutique_test', $boutiqueTest);
        $manager->persist($boutiqueTest);
        
        // Création de boutiques fictives via Faker
        $faker = Faker\Factory::create('fr_FR');
        $allVendeurs = $this->userRepository->getUserVendeur();
        for($i = 0; $i < count($allVendeurs); $i++){
            $imageBoutique = (new ImagesBoutique())
                ->setTitle($this->callApi->generateRandomGardenPictureUsingPixaBay());
            $manager->persist($imageBoutique);
            $boutiqueVendeur = (new Boutique())
                ->setTitle('La boutique de ' . $allVendeurs[$i]->getName())
                ->setDescription('Bonjour, je m\'appelle ' .$allVendeurs[$i]->getName(). ' et je vous présente ma boutique!')
                ->setTelephone($faker->phoneNumber);
            $this->setRandomAdress($boutiqueVendeur);
            $boutiqueVendeur
                ->setUser($allVendeurs[$i])
                ->setActif(1)
                ->setCardActive(1)
                ->setCreatedAt(new \DateTimeImmutable())
                ->addImagesBoutique( $imageBoutique);
            $this->addReference('boutique_'.$i , $boutiqueVendeur);
            $manager->persist($boutiqueVendeur);
        }
        $manager->flush();
    }

    private function setRandomAdress($boutiqueVendeur)
    {
        $boutiqueVendeur->setAdress('');
        $this->callApi->getCityInfosbyName($boutiqueVendeur);
    }
}
