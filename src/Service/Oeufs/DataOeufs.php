<?php

namespace App\Service\Oeufs;


class DataOeufs
{
    public function getOeufsData()
    {
        $oeufs = [
            [
                'cat' => 'Œufs',
                'title' => 'Autre',
            ],
            [
                'cat' => 'Œufs',
                'title' => 'Œuf d’oie',
            ],
            [
                'cat' => 'Œufs',
                'title' => 'Œuf de caille',
            ],
            [
                'cat' => 'Œufs',
                'title' => 'Œufs de poule',
            ],
        ];
        return $oeufs;
    }
}