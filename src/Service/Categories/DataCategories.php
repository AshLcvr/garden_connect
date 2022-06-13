<?php

namespace App\Service\Categories;


class DataCategories
{
    public function getCategoriesData()
    {
        $categories = [
            [
                'title' => 'Cereales Et Grains',
            ],
            [
                'title' => 'Engrais naturels et accessoires',
            ],
            [
                'title' => 'Farine',
            ],
            [
                'title' => 'Fruits',
            ],
            [
                'title' => 'Fruits à coques',
            ],
            [
                'title' => 'Herbes, épices, aromates, graines',
            ],
            [
                'title' => 'Huiles, miel et truffes',
            ],
            [
                'title' => 'Jus de fruits et de légumes',
            ],
            [
                'title' => 'Légumes et pois',
            ],
            [
                'title' => 'Œufs',
            ],
            [
                'title' => 'Panier',
            ],
            [
                'title' => 'Plants, plantes, feuilles, arbres, graines, pousses',
            ]

        ];
        return $categories;
    }
}