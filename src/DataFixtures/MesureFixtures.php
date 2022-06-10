<?php

namespace App\DataFixtures;

use App\Entity\Mesure;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MesureFixtures extends Fixture
{
    public function __construct()
    {
        $mesures = [
            ['title' => 'Kg'],
            ['title' => 'Gr'],
            ['title' => 'Barquette 250gr'],
            ['title' => 'litre'],
            ['title' => 'la pièce'],
            ['title' => 'l\'unité'],
            ['title' => 'la cagette'],
            ['title' => 'Barquette 500gr'],
            ['title' => 'la botte'],
            ['title' => 'la gousse'],
            ['title' => 'le bocal'],
            ['title' => 'le pot'],
            ['title' => 'le bouquet'],
            ['title' => 'les 100 gr'],
            ['title' => 'Les 250gr'],
            ['title' => 'Les 350g'],
            ['title' => 'Les 500gr'],
            ['title' => 'La douzaine'],
            ['title' => 'La portion'],
            ['title' => 'La bouteille'],
            ['title' => 'A DONNER'],
            ['title' => 'Les 10 gr'],
            ['title' => 'Les 20 gr'],
            ['title' => 'Les 30 gr'],
            ['title' => 'Les 50 gr'],
            ['title' => 'La barquette'],
            ['title' => 'Le panier'],
            ['title' => 'les 6'],
        ];

        $this->mesures = $mesures;
    }


    public function load(ObjectManager $manager): void
    {
        foreach ($this->mesures as $mesure){
            $newMesure = new Mesure();
            $newMesure->setTitle($mesure['title']);
            $manager->persist($newMesure);
            $this->addReference($mesure['title'], $newMesure);
        }

        $manager->flush();
    }
}
