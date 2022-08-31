<?php

namespace App\DataFixtures;

use App\Repository\BoutiqueRepository;
use App\Repository\SubcategoryRepository;
use App\Service\CallApi;
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
    private $callApi;

    public function __construct(
        BoutiqueRepository $boutiqueRepository,
        SubcategoryRepository $subcategoryRepository,
        CallApi $callApi
    ){
        $this->boutiqueRepository    = $boutiqueRepository;
        $this->subcategoryRepository = $subcategoryRepository;
        $this->callApi               = $callApi;
    }

    public function load(ObjectManager $manager): void
    {
//        // Création d'annonces fictives via Faker
        $allBoutiques = $this->boutiqueRepository->findAll();
        foreach ($allBoutiques as $boutique){
            if ($boutique->getId() !== 1 ){
                for($i = 0; $i <= random_int(2,5) ; $i++) {
                    $randSubcat = $this->subcategoryRepository->randomSubcategory();
                    $randCat    = $randSubcat->getParentCategory();

                    $annonce = new Annonce();
                    $annonce->setTitle($randSubcat->getTitle());
                    $annonce->setDescription($this->callApi->generateLipsumusingAsdfast(6,25))
                        ->setPrice(random_int(1, 10))
                        ->setMesure($this->getReference('Kg'))
                        ->setSubcategory($randSubcat)
                        ->setBoutique($boutique)
                        ->setActif(true)
                        ->setCreatedAt(new DateTimeImmutable('-2 weeks'));
                    for ($i = 1; $i < random_int(2,4); $i++){
                        $imageAnnonce = (new ImagesAnnonces())
                            ->setTitle($this->callApi->generateRandomAnnoncePicturesUsingPixaBay($randSubcat->getTitle(),$randCat));
                        $annonce->addImagesAnnonce($imageAnnonce);
                        $manager->persist($imageAnnonce);
                    }
                    $manager->persist($annonce);
                }
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
