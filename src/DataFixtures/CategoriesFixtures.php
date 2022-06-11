<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Subcategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Service\Categories\DataCategories;
use App\Service\CerealesEtGrains\DataCerealesEtGrains;
use App\Service\EngraisNaturelsEtAccessoires\DataEngraisNaturelsEtAccessoires;
use App\Service\Farine\DataFarine;
use App\Service\Fruits\DataFruits;
use App\Service\FruitsACoques\DataFruitsACoques;
use App\Service\HerbesEpicesAromatesGraines\DataHerbesEpicesAromatesGraines;
use App\Service\HuilesMielTruffes\DataHuilesMielTruffes;
use App\Service\JusFruitsLegumes\DataJusFruitsLegumes;
use App\Service\LegumesEtPois\DataLegumesEtPois;
use App\Service\Oeufs\DataOeufs;
use App\Service\Panier\DataPanier;
use App\Service\PlantsPlantesFeuillesArbresGrainesPousses\DataPlantsPlantesFeuillesArbresGrainesPousses;

class CategoriesFixtures extends Fixture
{
    public function __construct()
    {
        $categories = new DataCategories();
        $this->categories = $categories->getCategoriesData();

        $cerealesEtGrains = new DataCerealesEtGrains();
        $engraisNaturelsEtAccessoires = new DataEngraisNaturelsEtAccessoires();
        $farine = new DataFarine();
        $fruits = new DataFruits();
        $fruitsACoques = new DataFruitsACoques();
        $herbesEpicesAromatesGraines = new DataHerbesEpicesAromatesGraines();
        $huilesMielTruffes = new DataHuilesMielTruffes();
        $jusFruitsLegumes = new DataJusFruitsLegumes();
        $legumesEtPois = new DataLegumesEtPois();
        $oeufs = new DataOeufs();
        $panier = new DataPanier();
        $plantsPlantesFeuillesArbresGrainesPousses = new DataPlantsPlantesFeuillesArbresGrainesPousses();

        $sousCats   = [];
        $sousCats[] =  $cerealesEtGrains->getCerealesEtGrainsData();
        $sousCats[] =  $engraisNaturelsEtAccessoires->getEngraisNaturelsEtAccessoiresData();
        $sousCats[] =  $farine->getFarineData();
        $sousCats[] =  $fruits->getFruitsData();
        $sousCats[] =  $fruitsACoques->getFruitsACoquesData();
        $sousCats[] =  $herbesEpicesAromatesGraines->getHerbesEpicesAromatesGrainesData();
        $sousCats[] =  $huilesMielTruffes->getHuilesMielTruffesData();
        $sousCats[] =  $jusFruitsLegumes->getJusFruitsLegumesData();
        $sousCats[] =  $legumesEtPois->getLegumesEtPoisData();
        $sousCats[] =  $oeufs->getOeufsData();
        $sousCats[] =  $panier->getPanierData();
        $sousCats[] =  $plantsPlantesFeuillesArbresGrainesPousses->getPlantsPlantesFeuillesArbresGrainesPoussesData();

        $this->sousCats = $sousCats;
    }
    public function load(ObjectManager $manager): void
    {
        foreach ($this->categories as $key => $catParent) {
            $categorieP = new Category();
            $categorieP->setTitle($catParent['title']);
            $manager->persist($categorieP);

            foreach ($this->sousCats as $catEnfant) {
                foreach ($catEnfant as $key => $value) {
                    if ($value['cat'] === $catParent['title']) {
                        $categorieE = new Subcategory();
                        $categorieE->setTitle($value['title']);
                        $categorieE->setParentCategory($categorieP);
                        $manager->persist($categorieE);
                    }
                }
            }
        }
        $categorieE2 = new Subcategory();
        $categorieE2->setTitle('Pain');
        $categorieE2->setParentCategory($categorieP);
        $manager->persist($categorieE2);

        $manager->flush();
        $this->addReference('Miel', $categorieE);
        $this->addReference('Avoine', $categorieE2);


    }
}
