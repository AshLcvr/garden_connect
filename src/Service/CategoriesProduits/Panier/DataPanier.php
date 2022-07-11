<?php

namespace App\Service\CategoriesProduits\Panier;


class DataPanier
{
    public function getPanierData()
    {
        $panier = [
            [
                'cat' => 'Panier',
                'title' => 'Panier fruits',
            ],
            [
                'cat' => 'Panier',
                'title' => 'Panier légumes',
            ],
            [
                'cat' => 'Panier',
                'title' => 'Panier primeur',
            ],
        ];
        return $panier;
    }
}