<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Subcategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Service\Categories\DataCategories;
use App\Service\CerealesEtGrains\DataCerealesEtGrains;
use App\Service\EngraisNaturelsEtAccessoires\DataEngraisNaturelsEtAccessoires;

class CategoriesFixtures extends Fixture
{
    public function __construct()
    {
        $categories = new DataCategories();
        $this->categories = $categories->getCategoriesData();

        $cerealesEtGrains = new DataCerealesEtGrains();
        $engraisNaturelsEtAccessoires = new DataEngraisNaturelsEtAccessoires();

        $sousCats   = [];
        $sousCats[] =  $cerealesEtGrains->getCerealesEtGrainsData();
        $sousCats[] =  $engraisNaturelsEtAccessoires->getEngraisNaturelsEtAccessoiresData();

        $this->sousCats = $sousCats;
    }
    public function load(ObjectManager $manager): void
    {
        foreach ($this->categories as $key => $catParent) {
            $categorieP = new Category();
            $categorieP->setTitle($catParent['title']);
            $manager->persist($categorieP);
            $this->addReference(str_replace(' ', '', $catParent['title']), $categorieP);
            $manager->flush();


            foreach ($this->sousCats as $catEnfant) {
                foreach ($catEnfant as $value) {
                    if ($value['cat'] === $catParent['title']) {
                        $categorieE = new Category();
                        $categorieE->setTitle($value['title']);
                        $categorieE->setParentId($categorieP->getId());
                        $manager->persist($categorieE);
                    }
                }
            }
        }
        $manager->flush();
    }
}
