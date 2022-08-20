<?php

namespace App\Controller\Boutique;

use App\Entity\Favory;
use App\Entity\Boutique;
use App\Repository\FavoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/boutique')]
class FavoryController extends AbstractController
{
    #[Route('/favorites/listing', name: 'app_listing_favory')]
    public function listingFavorites(Request $request, PaginatorInterface $paginator): Response
    {
        $user     = $this->getUser();
        $favories = $user->getFavories();

        $globalRating = [];
        $totalGlobalRating = [];
        $numberAvis = [];
        $totalNumberAvis = [];
        $total = [];

        if ($favories){
            foreach ($favories as $key => $fav) {
                $numberAvis = count($fav->getBoutique()->getAvis());
                $totalNumberAvis[$fav->getBoutique()->getId()] = $numberAvis;
                foreach ($fav->getBoutique()->getAvis() as $key => $avi) {
                    $total[] = $avi->getRating();
                }
                if ($numberAvis) {
                    $globalRating[$fav->getBoutique()->getId()] = array_sum($total)/$numberAvis;
                    $total = [];
                    $totalGlobalRating[$fav->getBoutique()->getId()] = $globalRating[$fav->getBoutique()->getId()];
                    $globalRating = [];
                }
            }
        }else{
            $totalGlobalRating = 0;
        }
        $favories = $this->maPagination($favories, $paginator, $request, 5);

        return $this->render('front/boutique/favorites/listing.html.twig',[
            'favories' => $favories,
            'totalGlobalRating' => $totalGlobalRating,
            'totalNumberAvis' => $totalNumberAvis,
        ]);
    }

    #[Route('/favorites/removes/{id}', name: 'app_remove_favory', methods : ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function removeFavory(Request $request, Favory $favory, FavoryRepository $favoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$favory->getId(), $request->request->get('_token'))) {
            $favoryRepository->remove($favory, true);
        }

        return $this->redirectToRoute('app_listing_favory', [], Response::HTTP_SEE_OTHER);
    }
}
