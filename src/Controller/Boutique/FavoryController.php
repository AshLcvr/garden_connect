<?php

namespace App\Controller\Boutique;

use App\Entity\Boutique;
use App\Entity\Favory;
use App\Repository\FavoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavoryController extends AbstractController
{
    #[Route('/favory/{id}', name: 'app_toggle_favory', methods : ['GET', 'POST'])]
    public function toggleFavory(Boutique $boutique, FavoryRepository $favoryRepository): Response
    {
        $user = $this->getUser();
        $alreadyFavory = $favoryRepository->findOneBy(['user'=> $user, 'boutique' => $boutique]);
        if (empty($alreadyFavory)){
            $newFavory = new Favory();
            $newFavory
                ->setBoutique($boutique)
                ->setUser($user);
            $favoryRepository->add($newFavory,true);
        }else{
            $favoryRepository->remove($alreadyFavory,true);
        }

        return $this->redirectToRoute('view_boutique', ['id' => $boutique->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/favorites/listing', name: 'app_listing_favory', methods : ['GET', 'POST'])]
    public function listingFavorites(FavoryRepository $favoryRepository): Response
    {
        $user = $this->getUser();
        $favorites = $favoryRepository->findBy(['user'=> $user]);

        return $this->render('front/boutique/favorites/listing.html.twig',[
            'favorites' => $favorites
        ]);
    }

    #[Route('/favorites/remove/{id}', name: 'app_remove_favory', methods : ['GET', 'POST'])]
    public function removeFavory(Boutique $boutique ,FavoryRepository $favoryRepository): Response
    {
        $user = $this->getUser();
        $favorites = $favoryRepository->findOneBy(['boutique'=> $boutique, 'user' => $user]);
        $favoryRepository->remove($favorites,true);

        return $this->redirectToRoute('app_listing_favory', [], Response::HTTP_SEE_OTHER);
    }
}
