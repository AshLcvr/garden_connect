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

        $boutiquePolo = (new Boutique())
            ->setTitle('La boutique de Polo')
            ->setDescription('La boutique qu\'elle est belle')
            ->setAdress('')
            ->setCity('Nointot')
            ->setPostcode(76210)
            ->setCitycode(76468)
            ->setLng(0.470229)
            ->setLat(49.596299)
            ->setTelephone('0744556677')
            ->setUser($this->getReference('polo'))
            ->setActif(1)
            ->setCardActive(1)
            ->addImagesBoutique($imageBoutique)
            ->setCreatedAt(new \DateTimeImmutable('-2 week'));
        $this->addReference('boutique_polo', $boutiquePolo);
        $manager->persist($boutiquePolo);

        $imageBoutique2 = (new ImagesBoutique())
            ->setTitle('imageBoutiqueDefault.jpg');
            $manager->persist($imageBoutique2);

        $boutiqueSacha= (new Boutique())
            ->setTitle('La boutique de Sacha')
            ->setDescription('La boutique qu\'elle est belle')
            ->setAdress('')
            ->setCity('Routot')
            ->setPostcode(27350)
            ->setCitycode(27500)
            ->setLng(0.729845)
            ->setLat(49.374605)
            ->setUser($this->getReference('sacha'))
            ->setActif(1)
            ->setCardActive(1)
            ->setCreatedAt(new \DateTimeImmutable())
            ->addImagesBoutique($imageBoutique2);
        $this->addReference('boutique_sacha', $boutiqueSacha);
        $manager->persist($boutiqueSacha);

        $imageBoutique3 = (new ImagesBoutique())
            ->setTitle('imageBoutiqueDefault.jpg');
            $manager->persist($imageBoutique3);

        $boutiqueOrianne = (new Boutique())
            ->setTitle('La boutique de Orianne')
            ->setDescription('La boutique qu\'elle est belle. 3 wolf moon banh mi vaporware raclette, DSA XOXO single-origin coffee chicharrones chillwave yuccie church-key vinyl small batch. Shoreditch paleo readymade narwhal pork belly four loko. Fashion axe master cleanse salvia, vexillologist flannel taxidermy swag four loko jean shorts kale chips hoodie. 3 wolf moon banh mi vaporware raclette, DSA XOXO single-origin coffee chicharrones chillwave yuccie church-key vinyl small batch. Shoreditch paleo readymade narwhal pork belly four loko. Fashion axe master cleanse salvia, vexillologist flannel taxidermy swag four loko jean shorts kale chips hoodie. 3 wolf moon banh mi vaporware raclette, DSA XOXO single-origin coffee chicharrones chillwave yuccie church-key vinyl small batch. Shoreditch paleo readymade narwhal pork belly four loko. Fashion axe master cleanse salvia, vexillologist flannel taxidermy swag four loko jean shorts kale chips hoodie.')
            ->setAdress('')
            ->setCity('Pont-Audemer')
            ->setPostcode(27500)
            ->setCitycode(27467)
            ->setLng(0.525508)
            ->setLat(49.346658)
            ->setUser($this->getReference('orianne'))
            ->setActif(1)
            ->setCardActive(1)
            ->setCreatedAt(new \DateTimeImmutable())
            ->addImagesBoutique($imageBoutique3);
        $this->addReference('boutique_orianne', $boutiqueOrianne);
        $manager->persist($boutiqueOrianne);

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
