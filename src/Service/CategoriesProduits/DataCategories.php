<?php

namespace App\Service\CategoriesProduits;


class DataCategories
{
    public function getCategoriesData()
    {
        $categories = [
            [
                'title' => 'Cereales Et Grains',
                'image' => 'cereales-et-grains-image.jpg'
            ],
            [
                'title' => 'Engrais naturels et accessoires',
                'image' => 'engrais-naturels-et-accessoires-image.jpg'
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
                'image' => 'fruits-à-coques-image.jpg'
            ],
            [
                'title' => 'Herbes, épices, aromates, graines',
                'image' => 'herbes-épices-aromates-graines-image.jpg'
            ],
            [
                'title' => 'Huiles, miel et truffes',
                'image' => 'huiles-miel-et-truffes-image.jpg'
            ],
            [
                'title' => 'Jus de fruits et de légumes',
                'image' => 'jus-de-fruits-et-de-légumes-image.jpg'
            ],
            [
                'title' => 'Légumes et pois',
                'image' => 'légumes-et-pois-image.jpg'
            ],
            [
                'title' => 'Œufs',
                'image' => 'œufs-image.jpg'
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