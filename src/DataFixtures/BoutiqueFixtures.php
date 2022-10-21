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
            ->setTelephone('0695680086')
            ->setUser($this->getReference('vendeur_0'))
            ->setActif(1)
            ->setCardActive(1)
            ->addImagesBoutique($imageBoutique)
            ->setCreatedAt(new \DateTimeImmutable('-2 week'));
        $this->addReference('boutique_test', $boutiqueTest);
        $manager->persist($boutiqueTest);
        
        // Création de boutiques fictives via Faker
        $faker       = Faker\Factory::create('fr_FR');
        $allVendeurs = $this->userRepository->getUserVendeur();

        for($i = 0; $i < count($allVendeurs); $i++){

            $imageBoutique = (new ImagesBoutique())
                ->setTitle($this->setRandomGardenPicture());
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
                ->setCreatedAt($allVendeurs[$i]->getCreatedAt())
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

    private function setRandomGardenPicture()
    {
        $randImageBoutique = ['1-azalea-5120368-1920-6321a2eb213f3.jpg','1-castle-5511046-1920-6321a2ea972f4.jpg', '1-country-house-2699713-1920-6321a2ffdfa91.jpg', '1-finland-909742-1920-6321a2ebda6ba.jpg', '1-hd-wallpaper-3251607-1920-6321a2eb82875.jpg', '1-insect-1278820-1920-6321a2d67ecaf.jpg', '1-lavender-1507499-1920-6321a2d6dfb53.jpg', '1-marguerite-1507550-1920-6321a3005102f.jpg', '1-marguerite-1510602-1920-6321a311bd965.jpg', '1-pathway-2289978-1920-6321a3123c5f8.jpg', '1-poppies-3441348-1920-6321a2d621445.jpg', '1-poppy-3137588-1920-6321a3115bdb3.jpg','1-stones-2040340-1920-6321a312a5f65.jpg', '1-vegetables-landscape-2943500-1920-6321a2d5a78bb.jpg', '1-wheelbarrow-1232408-1920-6321a2ff04fbd.jpg', '1-white-cabbage-2521700-1920-6321a2ff76805.jpg'];
        $imageIndex = array_rand($randImageBoutique);
        $imageTitle = $randImageBoutique[$imageIndex];

        return $imageTitle;
    }
}
