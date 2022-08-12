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
                $randSubcat = $this->subcategoryRepository->randomSubcategory()[0];
                $randCat = $randSubcat->getParentCategory();

                $imageAnnonce = (new ImagesAnnonces())
                    ->setTitle($randCat->getImage());

                $annonce = new Annonce();
                $annonce->setTitle($randSubcat->getTitle());
                $randText = json_decode(file_get_contents('http://asdfast.beobit.net/api/?length='.random_int(6,50).'&type=word'));
                $annonce->setDescription($randText->text)
                ->setPrice(random_int(1, 10))
                ->setMesure($this->getReference('Kg'))
                ->setSubcategory($randSubcat)
                ->setBoutique($boutique)
                ->setActif(true)
                ->addImagesAnnonce($imageAnnonce)
                ->setCreatedAt(new DateTimeImmutable('-2 weeks'));
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
