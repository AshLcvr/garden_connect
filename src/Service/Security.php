<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;

class Security
{

    public function security($boutique_user, $boutique_users)
    {
        $tableau = [];
        foreach ($boutique_users as $key => $value) {
            $tableau[] = $value;
        }
        if (!in_array($boutique_user, $tableau, true)) {
            $security = false;
        }
        if ($security === false){
            return $this->redirectToRoute('403',[], Response::HTTP_SEE_OTHER);
        }
    }
}