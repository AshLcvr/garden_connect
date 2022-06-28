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

class FavoryController extends AbstractController
{
    #[Route('/favory/{id}', name: 'app_toggle_favory', methods : ['GET', 'POST'], requirements: ['id' => '\d+'])]
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

    #[Route('/favorites/listing', name: 'app_listing_favory')]
    public function listingFavorites(Request $request, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();
        $favories = $user->getFavories();

        $globalRating = [];
        $totalGlobalRating = [];
        $numberAvis = [];
        $totalNumberAvis = [];

        if ($favories){
            foreach ($favories as $key => $fav) {
                $numberAvis = count($fav->getBoutique()->getAvis());
                $totalNumberAvis[$fav->getBoutique()->getId()] = count($fav->getBoutique()->getAvis());
                foreach ($fav->getBoutique()->getAvis() as $key => $value) {
                    $globalRating[] = $value->getRating();
                }
                if ($numberAvis) {
                    $totalGlobalRating[$fav->getBoutique()->getId()] = round(array_sum($globalRating)/$numberAvis);
                    $globalRating = [];
                }
            }
        }else{
            $totalGlobalRating = 0;
        }
        
        $favories = $paginator->paginate(
            $favories, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            5 // Nombre de résultats par page
        );

        return $this->render('front/boutique/favorites/listing.html.twig',[
            'favories' => $favories,
            'totalGlobalRating' => $totalGlobalRating,
            'totalNumberAvis' => $totalNumberAvis
        ]);
    }

    #[Route('/favorites/removesdfb/{id}', name: 'app_remove_favory', methods : ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function removeFavory(Request $request, Favory $favory, FavoryRepository $favoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$favory->getId(), $request->request->get('_token'))) {
            $favoryRepository->remove($favory, true);
        }

        return $this->redirectToRoute('app_listing_favory', [], Response::HTTP_SEE_OTHER);
    }
}
