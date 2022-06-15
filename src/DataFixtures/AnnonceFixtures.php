<?php

namespace App\DataFixtures;

use DateTimeImmutable;
use App\Entity\Annonce;
use App\Entity\ImagesAnnonces;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AnnonceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        $imageAnnonce = (new ImagesAnnonces())
            ->setTitle('17-quinoa-62a89fb5a9776.jpg');
        $annonce = new Annonce();
        $annonce->setTitle('Quinoa');
        $annonce->setDescription('Quinoa ! Intelligentsia tofu echo park, jean shorts cred typewriter crucifix leggings next level.');
        $annonce->setPrice(5);
        $annonce->setMesure($this->getReference('Kg'));
        $annonce->setCreatedAt(new DateTimeImmutable('-2 week'));
        $annonce->setActif(true);
        $annonce->addImagesAnnonce($imageAnnonce);
        $annonce->setBoutique($this->getReference('boutique_polo'));
        $annonce->setSubcategory($this->getReference('Avoine'));
        $manager->persist($annonce);
        $manager->persist($imageAnnonce);

        $imageAnnonce2 = (new ImagesAnnonces())
            ->setTitle('17-ble-62a8ae8a836a1.jpg');
        $annonce2 = new Annonce();
        $annonce2->setTitle('Blé');
        $annonce2->setDescription('Blé de Pont-Audemer ! Intelligentsia tofu echo park, jean shorts cred typewriter crucifix leggings next level.');
        $annonce2->setPrice(5);
        $annonce2->setMesure($this->getReference('Kg'));
        $annonce2->setCreatedAt(new DateTimeImmutable());
        $annonce2->setActif(true);
        $annonce2->addImagesAnnonce($imageAnnonce2);
        $annonce2->setBoutique($this->getReference('boutique_sacha'));
        $annonce2->setSubcategory($this->getReference('Blé'));
        $manager->persist($annonce2);
        $manager->persist($imageAnnonce2);

        $imageAnnonce3 = (new ImagesAnnonces())
            ->setTitle('17-boulgour-62a8aa52ec283.jpg');
        $annonce3 = new Annonce();
        $annonce3->setTitle('Boulghour');
        $annonce3->setDescription('Boulghour de Corneville ! Intelligentsia tofu echo park, jean shorts cred typewriter crucifix leggings next level.');
        $annonce3->setPrice(10);
        $annonce3->setMesure($this->getReference('Kg'));
        $annonce3->setCreatedAt(new DateTimeImmutable());
        $annonce3->setActif(true);
        $annonce3->addImagesAnnonce($imageAnnonce3);
        $annonce3->setBoutique($this->getReference('boutique_orianne'));
        $annonce3->setSubcategory($this->getReference('Boulghour'));
        $manager->persist($annonce3);
        $manager->persist($imageAnnonce3);

        $imageAnnonce4 = (new ImagesAnnonces())
            ->setTitle('17-epeautre-62a8aa5e19d9d.jpg');
        $annonce4 = new Annonce();
        $annonce4->setTitle('Épeautre');
        $annonce4->setDescription('Épeautre de Corneville ! Intelligentsia tofu echo park, jean shorts cred typewriter crucifix leggings next level.');
        $annonce4->setPrice(5);
        $annonce4->setMesure($this->getReference('Kg'));
        $annonce4->setCreatedAt(new DateTimeImmutable());
        $annonce4->setActif(true);
        $annonce4->addImagesAnnonce($imageAnnonce4);
        $annonce4->setBoutique($this->getReference('boutique_orianne'));
        $annonce4->setSubcategory($this->getReference('Épeautre'));
        $manager->persist($annonce4);
        $manager->persist($imageAnnonce4);

        $imageAnnonce5 = (new ImagesAnnonces())
            ->setTitle('17-mais-62a8aa44ee82c.jpg');
        $annonce5 = new Annonce();
        $annonce5->setTitle('Maïs');
        $annonce5->setDescription('Maïs de Corneville ! Intelligentsia tofu echo park, jean shorts cred typewriter crucifix leggings next level.');
        $annonce5->setPrice(6);
        $annonce5->setMesure($this->getReference('Kg'));
        $annonce5->setCreatedAt(new DateTimeImmutable());
        $annonce5->setActif(true);
        $annonce5->addImagesAnnonce($imageAnnonce5);
        $annonce5->setBoutique($this->getReference('boutique_orianne'));
        $annonce5->setSubcategory($this->getReference('Maïs'));
        $manager->persist($annonce5);
        $manager->persist($imageAnnonce5);

        $imageAnnonce6 = (new ImagesAnnonces())
            ->setTitle('17-compost-62a8aa2fee3a0.jpg');
        $annonce6 = new Annonce();
        $annonce6->setTitle('Compost');
        $annonce6->setDescription('Compost de Corneville ! Intelligentsia tofu echo park, jean shorts cred typewriter crucifix leggings next level.');
        $annonce6->setPrice(3);
        $annonce6->setMesure($this->getReference('Kg'));
        $annonce6->setCreatedAt(new DateTimeImmutable());
        $annonce6->setActif(true);
        $annonce6->addImagesAnnonce($imageAnnonce6);
        $annonce6->setBoutique($this->getReference('boutique_orianne'));
        $annonce6->setSubcategory($this->getReference('Compost'));
        $manager->persist($annonce6);
        $manager->persist($imageAnnonce6);

        $imageAnnonce7 = (new ImagesAnnonces())
            ->setTitle('17-foin-62a8aa0fe3899.jpg');
        $annonce7 = new Annonce();
        $annonce7->setTitle('Foins');
        $annonce7->setDescription('Foins de Corneville ! Intelligentsia tofu echo park, jean shorts cred typewriter crucifix leggings next level.');
        $annonce7->setPrice(12);
        $annonce7->setMesure($this->getReference('Kg'));
        $annonce7->setCreatedAt(new DateTimeImmutable());
        $annonce7->setActif(true);
        $annonce7->addImagesAnnonce($imageAnnonce7);
        $annonce7->setBoutique($this->getReference('boutique_orianne'));
        $annonce7->setSubcategory($this->getReference('Foins'));
        $manager->persist($annonce7);
        $manager->persist($imageAnnonce7);

        $imageAnnonce8 = (new ImagesAnnonces())
            ->setTitle('17-ortie-62a8a22474cfe.jpg');
        $annonce8 = new Annonce();
        $annonce8->setTitle('Ortie');
        $annonce8->setDescription('Ortie de Corneville ! Intelligentsia tofu echo park, jean shorts cred typewriter crucifix leggings next level.');
        $annonce8->setPrice(4);
        $annonce8->setMesure($this->getReference('Kg'));
        $annonce8->setCreatedAt(new DateTimeImmutable());
        $annonce8->setActif(true);
        $annonce8->addImagesAnnonce($imageAnnonce8);
        $annonce8->setBoutique($this->getReference('boutique_orianne'));
        $annonce8->setSubcategory($this->getReference('Ortie'));
        $manager->persist($annonce8);
        $manager->persist($imageAnnonce8);

        $imageAnnonce9 = (new ImagesAnnonces())
            ->setTitle('17-paille-62a8a21269d31.jpg');
        $annonce9 = new Annonce();
        $annonce9->setTitle('Paille');
        $annonce9->setDescription('Paille de Corneville ! Intelligentsia tofu echo park, jean shorts cred typewriter crucifix leggings next level.');
        $annonce9->setPrice(9);
        $annonce9->setMesure($this->getReference('Kg'));
        $annonce9->setCreatedAt(new DateTimeImmutable());
        $annonce9->setActif(true);
        $annonce9->addImagesAnnonce($imageAnnonce9);
        $annonce9->setBoutique($this->getReference('boutique_orianne'));
        $annonce9->setSubcategory($this->getReference('Paille'));
        $manager->persist($annonce9);
        $manager->persist($imageAnnonce9);

        $imageAnnonce10 = (new ImagesAnnonces())
            ->setTitle('17-purin-62a8a20943a86.jpg');
        $annonce10 = new Annonce();
        $annonce10->setTitle('Purin');
        $annonce10->setDescription('Purin de Corneville ! Intelligentsia tofu echo park, jean shorts cred typewriter crucifix leggings next level.');
        $annonce10->setPrice(20);
        $annonce10->setMesure($this->getReference('Kg'));
        $annonce10->setCreatedAt(new DateTimeImmutable());
        $annonce10->setActif(true);
        $annonce10->addImagesAnnonce($imageAnnonce10);
        $annonce10->setBoutique($this->getReference('boutique_orianne'));
        $annonce10->setSubcategory($this->getReference('Purin'));
        $manager->persist($annonce10);
        $manager->persist($imageAnnonce10);

        $imageAnnonce11 = (new ImagesAnnonces())
            ->setTitle('17-chataigne-62a8a1d571b61.jpg');
        $annonce11 = new Annonce();
        $annonce11->setTitle('Chataîgne');
        $annonce11->setDescription('Chataîgne de Corneville ! Intelligentsia tofu echo park, jean shorts cred typewriter crucifix leggings next level.');
        $annonce11->setPrice(15);
        $annonce11->setMesure($this->getReference('Kg'));
        $annonce11->setCreatedAt(new DateTimeImmutable());
        $annonce11->setActif(true);
        $annonce11->addImagesAnnonce($imageAnnonce11);
        $annonce11->setBoutique($this->getReference('boutique_orianne'));
        $annonce11->setSubcategory($this->getReference('Chataîgne'));
        $manager->persist($annonce11);
        $manager->persist($imageAnnonce11);

        $imageAnnonce12 = (new ImagesAnnonces())
            ->setTitle('17-pomme-62a8ae49eb208.jpg');
        $annonce12 = new Annonce();
        $annonce12->setTitle('Pommes');
        $annonce12->setDescription('Mélange de Corneville ! Intelligentsia tofu echo park, jean shorts cred typewriter crucifix leggings next level.');
        $annonce12->setPrice(7);
        $annonce12->setMesure($this->getReference('Kg'));
        $annonce12->setCreatedAt(new DateTimeImmutable());
        $annonce12->setActif(true);
        $annonce12->addImagesAnnonce($imageAnnonce12);
        $annonce12->setBoutique($this->getReference('boutique_orianne'));
        $annonce12->setSubcategory($this->getReference('Pomme'));
        $manager->persist($annonce12);
        $manager->persist($imageAnnonce12);

        $imageAnnonce13 = (new ImagesAnnonces())
            ->setTitle('17-maches-62a8b01573fb6.jpg');
        $annonce13 = new Annonce();
        $annonce13->setTitle('Laitue');
        $annonce13->setDescription('Riz de Corneville ! Intelligentsia tofu echo park, jean shorts cred typewriter crucifix leggings next level.');
        $annonce13->setPrice(8);
        $annonce13->setMesure($this->getReference('Kg'));
        $annonce13->setCreatedAt(new DateTimeImmutable());
        $annonce13->setActif(true);
        $annonce13->addImagesAnnonce($imageAnnonce13);
        $annonce13->setBoutique($this->getReference('boutique_orianne'));
        $annonce13->setSubcategory($this->getReference('Mâche'));
        $manager->persist($annonce13);
        $manager->persist($imageAnnonce13);

        $imageAnnonce14 = (new ImagesAnnonces())
            ->setTitle('17-pdt-62a8b0f224aa6.jpg');
        $annonce14 = new Annonce();
        $annonce14->setTitle('Radis');
        $annonce14->setDescription('Sarrasin de Corneville ! Intelligentsia tofu echo park, jean shorts cred typewriter crucifix leggings next level.');
        $annonce14->setPrice(6);
        $annonce14->setMesure($this->getReference('Kg'));
        $annonce14->setCreatedAt(new DateTimeImmutable());
        $annonce14->setActif(true);
        $annonce14->addImagesAnnonce($imageAnnonce14);
        $annonce14->setBoutique($this->getReference('boutique_orianne'));
        $annonce14->setSubcategory($this->getReference('Pomme de terre'));
        $manager->persist($annonce14);
        $manager->persist($imageAnnonce14);

        $imageAnnonce15 = (new ImagesAnnonces())
            ->setTitle('17-seigle-62a8af33589f8.jpg');
        $annonce15 = new Annonce();
        $annonce15->setTitle('Seigle');
        $annonce15->setDescription('Seigle de Corneville ! Intelligentsia tofu echo park, jean shorts cred typewriter crucifix leggings next level.');
        $annonce15->setPrice(12);
        $annonce15->setMesure($this->getReference('Kg'));
        $annonce15->setCreatedAt(new DateTimeImmutable());
        $annonce15->setActif(true);
        $annonce15->addImagesAnnonce($imageAnnonce15);
        $annonce15->setBoutique($this->getReference('boutique_orianne'));
        $annonce15->setSubcategory($this->getReference('Seigle'));
        $manager->persist($annonce15);
        $manager->persist($imageAnnonce15);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            BoutiqueFixtures::class,
            CategoriesFixtures::class
        ];
    }
}
