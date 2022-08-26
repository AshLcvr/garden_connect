<?php

namespace App\Service\CategoriesProduits;


class DataCategories
{
    public function getCategoriesData()
    {
        $categories = [
            [
                'title' => 'Céréales et grains',
                'image' => 'cerealesetgrains-image.jpg'
            ],
            [
                'title' => 'Engrais naturels et accessoires',
                'image' => 'engraisnaturelsetaccessoires-image.jpg'
            ],
            [
                'title' => 'Farine',
                'image' => 'farine-image.jpg'
            ],
            [
                'title' => 'Fruits',
                'image' => 'fruits-image.jpg'
            ],
            [
                'title' => 'Fruits à coques',
                'image' => 'fruitsàcoques-image.jpg'
            ],
            [
                'title' => 'Herbes, épices, aromates, graines',
                'image' => 'herbes-épices-aromates-graines-image.jpg'
            ],
            [
                'title' => 'Huiles, miel et truffes',
                'image' => 'huiles-miel-ettruffes-image.jpg'
            ],
            [
                'title' => 'Jus de fruits et de légumes',
                'image' => 'jusdefruitsetdelégumes-image.jpg'
            ],
            [
                'title' => 'Légumes et pois',
                'image' => 'légumesetpois-image.jpg'
            ],
            [
                'title' => 'Œufs',
                'image' => 'œuf-image.jpg'
            ],
            [
                'title' => 'Panier',
                'image' => 'panier-image.jpg'
            ],
            [
                'title' => 'Plants, plantes, feuilles, arbres, graines, pousses',
                'image' => 'plants-plantes-feuilles-arbres-graines-pousses-image.jpg'
            ]
        ];
        return $categories;
    }
}