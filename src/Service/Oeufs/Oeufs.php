<?php

namespace App\Service\Oeufs;


class DataOeufs
{
    public function getOeufsData()
    {
        $oeufs = [
            [
                'cat' => 'Oeufs',
                'title' => 'Autre',
            ],
            [
                'cat' => 'Oeufs',
                'title' => 'Oeuf d’oie',
            ],
            [
                'cat' => 'Oeufs',
                'title' => 'Oeuf de caille',
            ],
            [
                'cat' => 'Oeufs',
                'title' => 'Oeufs de poule',
            ],
        ];
        return $oeufs;
    }
}