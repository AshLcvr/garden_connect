<?php

namespace App\Service\Categories;


class DataCategories
{
    public function getCategoriesData()
    {
        $categories = [
            [
                'title' => 'CerealesEtGrains',
            ],
            [
                'title' => 'EngraisNaturelsEtAccessoires',
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
                'title' => 'œufs',
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