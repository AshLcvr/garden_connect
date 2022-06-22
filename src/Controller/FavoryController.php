<?php

namespace App\Controller;

use App\Entity\Boutique;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavoryController extends AbstractController
{
    #[Route('/favory/{id}', name: 'app_toggle_favory', methods : ['GET', 'POST'])]
    public function toggleFavory(Boutique $boutique, UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        $boutique_fav = $user->getBoutiqueFavory();
        if($boutique_fav == null){
            $user->addBoutiqueFavory($boutique);
        }else{
            foreach ($boutique_fav  as  $boutique_f) {
                if ($boutique_f == $boutique) {
//                dd('gg');
                    $user->removeBoutiqueFavory($boutique);
                }
//            else {
//                $user->addBoutiqueFavory($boutique);
//            }
            }
        }


        $userRepository->add($user,true);

        return $this->redirectToRoute('view_boutique', ['id' => $boutique->getId()], Response::HTTP_SEE_OTHER);
    }
}
