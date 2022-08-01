<?php

namespace App\DataFixtures;

use App\Repository\BoutiqueRepository;
use App\Repository\SubcategoryRepository;
use DateTimeImmutable;
use App\Entity\Annonce;
use App\Entity\ImagesAnnonces;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AnnonceFixtures extends Fixture implements DependentFixtureInterface
{
    private $boutiqueRepository;
    private $subcategoryRepository;

    public function __construct(BoutiqueRepository $boutiqueRepository, SubcategoryRepository $subcategoryRepository)
    {
        $this->boutiqueRepository    = $boutiqueRepository;
        $this->subcategoryRepository = $subcategoryRepository;
    }

    public function load(ObjectManager $manager): void
    {
        // Création d'annonces fictives via Faker
        $allBoutiques = $this->boutiqueRepository->findAll();
        foreach ($allBoutiques as $boutique){
            for($i = 0; $i < 2 ; $i++) {
                $imageAnnonce = (new ImagesAnnonces())
                    ->setTitle('20-seigle-62a9ba95f2a43.jpg');
                $randSubcat = $this->subcategoryRepository->randomSubcategory()[0];

                $annonce = new Annonce();
                $annonce->setTitle($randSubcat->getTitle());
                $annonce->setDescription(simplexml_load_file('http://www.lipsum.com/feed/xml?amount=1&what=paras&start=0')->lipsum);
                $annonce->setPrice(random_int(1, 10));
                $annonce->setMesure($this->getReference('Kg'));
                $annonce->setSubcategory($randSubcat);
                $annonce->setBoutique($boutique);
                $annonce->setActif(true);
                $annonce->addImagesAnnonce($imageAnnonce);
                $annonce->setCreatedAt(new DateTimeImmutable('-2 weeks'));
                $manager->persist($annonce);
                $manager->persist($imageAnnonce);
            }
        }
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
