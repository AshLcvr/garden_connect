<?php

namespace App\Service\Categories;


class DataCategories
{
    public function getCategoriesData()
    {
        $categories = [
            [
                'title' => 'Cereales Et Grains',
                'image' => 'wheat-gfdb4a63f3-1920-62a8840fc1d78.jpg'
            ],
            [
                'title' => 'Engrais naturels et accessoires',
                'image' => 'soil-g61f96676a-1920-62a884ba88f28.jpg'
            ],
            [
                'title' => 'Farine',
                'image' => 'flour-ge2e58be23-1920-62a8851fbd70a.jpg'
            ],
            [
                'title' => 'Fruits',
                'image' => 'fruits-g7fa42de58-1920-62a8857372e16.jpg'
            ],
            [
                'title' => 'Fruits à coques',
                'image' => 'fruitsacoque-62a885fb02f7f.jpg'
            ],
            [
                'title' => 'Herbes, épices, aromates, graines',
                'image' => 'spices-g1ec6e5a95-1920-62a8863acc74e.jpg'
            ],
            [
                'title' => 'Huiles, miel et truffes',
                'image' => 'olive-oil-g6dd4c12e6-1920-62a8867da2ffb.jpg'
            ],
            [
                'title' => 'Jus de fruits et de légumes',
                'image' => 'smoothies-g25c2b29e6-1920-62a886ba95ce0.jpg'
            ],
            [
                'title' => 'Légumes et pois',
                'image' => 'vegetables-gf1d0b76c0-1920-62a886d43be8c.jpg'
            ],
            [
                'title' => 'Œufs',
                'image' => 'eggs-g98e95f65d-1920-62a886ee61d6f.jpg'
            ],
            [
                'title' => 'Panier',
                'image' => 'vegetables-g28597417e-1920-62a88738c8573.jpg'
            ],
            [
                'title' => 'Plants, plantes, feuilles, arbres, graines, pousses',
                'image' => 'luisa-grass-gabe7a76c2-1920-62a88761bdeca.jpg'
            ]

        ];
        return $categories;
    }
}