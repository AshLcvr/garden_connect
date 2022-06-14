<?php

namespace App\DataFixtures;

use App\Entity\Boutique;
use App\Entity\ImagesBoutique;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class   BoutiqueFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $imageBoutique = (new ImagesBoutique())
            ->setTitle('imageBoutiqueDefault.jpg');
            $manager->persist($imageBoutique);

        $boutiquePolo = (new Boutique())
            ->setTitle('La boutique de Polo')
            ->setDescription('La boutique qu\'elle est belle')
            ->setCity('Nointot')
            ->setTelephone('0744556677')
            ->setUser($this->getReference('polo'))
            ->setActif(1)
            ->addImagesBoutique($imageBoutique)
            ->setCreatedAt(new \DateTimeImmutable('-2 week'));
        $manager->persist($boutiquePolo);

        $imageBoutique2 = (new ImagesBoutique())
            ->setTitle('imageBoutiqueDefault.jpg');
            $manager->persist($imageBoutique2);

        $boutiqueSacha= (new Boutique())
            ->setTitle('La boutique de Sacha')
            ->setDescription('La boutique qu\'elle est belle')
            ->setCity('Routot')
            ->setTelephone('0600998877')
            ->setUser($this->getReference('sacha'))
            ->setActif(1)
            ->setCreatedAt(new \DateTimeImmutable())
            ->addImagesBoutique($imageBoutique2);
        $manager->persist($boutiqueSacha);

        $imageBoutique3 = (new ImagesBoutique())
            ->setTitle('imageBoutiqueDefault.jpg');
            $manager->persist($imageBoutique3);

        $boutiqueOrianne = (new Boutique())
            ->setTitle('La boutique de Orianne')
            ->setDescription('La boutique qu\'elle est belle. 3 wolf moon banh mi vaporware raclette, DSA XOXO single-origin coffee chicharrones chillwave yuccie church-key vinyl small batch. Shoreditch paleo readymade narwhal pork belly four loko. Fashion axe master cleanse salvia, vexillologist flannel taxidermy swag four loko jean shorts kale chips hoodie. 3 wolf moon banh mi vaporware raclette, DSA XOXO single-origin coffee chicharrones chillwave yuccie church-key vinyl small batch. Shoreditch paleo readymade narwhal pork belly four loko. Fashion axe master cleanse salvia, vexillologist flannel taxidermy swag four loko jean shorts kale chips hoodie. 3 wolf moon banh mi vaporware raclette, DSA XOXO single-origin coffee chicharrones chillwave yuccie church-key vinyl small batch. Shoreditch paleo readymade narwhal pork belly four loko. Fashion axe master cleanse salvia, vexillologist flannel taxidermy swag four loko jean shorts kale chips hoodie.')
            ->setCity('Pont-Audemer')
            ->setTelephone('0677889900')
            ->setUser($this->getReference('orianne'))
            ->setActif(1)
            ->setCreatedAt(new \DateTimeImmutable())
            ->addImagesBoutique($imageBoutique3);
        $manager->persist($boutiqueOrianne);

        $manager->flush();

        $this->addReference('boutique_polo', $boutiquePolo);
        $this->addReference('boutique_sacha', $boutiqueSacha);
        $this->addReference('boutique_orianne', $boutiqueOrianne);
    }
}
