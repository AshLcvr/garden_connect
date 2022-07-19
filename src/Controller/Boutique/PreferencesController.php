<?php

namespace App\Controller\Boutique;

use App\Entity\Boutique;
use App\Repository\BoutiqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/boutique')]
class PreferencesController extends AbstractController
{
    #[Route('/mespreferences', name: 'app_boutique_preference')]
    public function indexPreference(){
        $user = $this->getUser();
        $boutiques = $user->getBoutiques();
        $boutique = $boutiques[0];
        return $this->render('front/boutique/preferences/mespreferences.html.twig', [
            'boutique' => $boutique
        ]);
    }

    #[Route('/mespreferences/card', name: 'app_toggle_Cardactive')]
    public function toggleCardActive(BoutiqueRepository $boutiqueRepository): Response
    {
        $user = $this->getUser();
        $boutiques = $user->getBoutiques();
        $boutique = $boutiques[0];
        if ($boutique->isCardActive()) {
            $boutique->setCardActive(0);
        }else{
            $boutique->setCardActive(1);
        }
        $boutiqueRepository->add($boutique,true);

        return $this->redirectToRoute('app_boutique_preference', [], Response::HTTP_SEE_OTHER);
    }
}