<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Subcategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Service\CategoriesProduits\DataCategories;
use App\Service\CategoriesProduits\CerealesEtGrains\DataCerealesEtGrains;
use App\Service\CategoriesProduits\EngraisNaturelsEtAccessoires\DataEngraisNaturelsEtAccessoires;
use App\Service\CategoriesProduits\Farine\DataFarine;
use App\Service\CategoriesProduits\Fruits\DataFruits;
use App\Service\CategoriesProduits\FruitsACoques\DataFruitsACoques;
use App\Service\CategoriesProduits\HerbesEpicesAromatesGraines\DataHerbesEpicesAromatesGraines;
use App\Service\CategoriesProduits\HuilesMielTruffes\DataHuilesMielTruffes;
use App\Service\CategoriesProduits\JusFruitsLegumes\DataJusFruitsLegumes;
use App\Service\CategoriesProduits\LegumesEtPois\DataLegumesEtPois;
use App\Service\CategoriesProduits\Oeufs\DataOeufs;
use App\Service\CategoriesProduits\Panier\DataPanier;
use App\Service\CategoriesProduits\PlantsPlantesFeuillesArbresGrainesPousses\DataPlantsPlantesFeuillesArbresGrainesPousses;

class CategoriesFixtures extends Fixture
{

    public function __construct()
    {
        $categories       = new DataCategories();
        $this->categories = $categories->getCategoriesData();

        $cerealesEtGrains             = new DataCerealesEtGrains();
        $engraisNaturelsEtAccessoires = new DataEngraisNaturelsEtAccessoires();
        $farine                       = new DataFarine();
        $fruits                       = new DataFruits();
        $fruitsACoques                = new DataFruitsACoques();
        $herbesEpicesAromatesGraines  = new DataHerbesEpicesAromatesGraines();
        $huilesMielTruffes            = new DataHuilesMielTruffes();
        $jusFruitsLegumes             = new DataJusFruitsLegumes();
        $legumesEtPois                = new DataLegumesEtPois();
        $oeufs                        = new DataOeufs();
        $panier                       = new DataPanier();
        $plantsPlantesFeuillesArbres  = new DataPlantsPlantesFeuillesArbresGrainesPousses();

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
        $sousCats[] =  $plantsPlantesFeuillesArbres->getPlantsPlantesFeuillesArbresGrainesPoussesData();

        $this->sousCats = $sousCats;
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->categories as $key => $catParent) {
            $categorieP = new Category();
            $categorieP->setTitle($catParent['title']);
            $categorieP->setImage($catParent['image']);
            $manager->persist($categorieP);

            foreach ($this->sousCats as $catEnfant) {
                foreach ($catEnfant as $key => $value) {
                    if ($value['cat'] === $catParent['title']) {
                        $categorieE = new Subcategory();
                        $categorieE->setTitle($value['title']);
                        $categorieE->setParentCategory($categorieP);
                        $this->setReference($value['title'], $categorieE);
                        $manager->persist($categorieE);
                    }
                }
            }
        }
        $manager->flush();
    }
}
