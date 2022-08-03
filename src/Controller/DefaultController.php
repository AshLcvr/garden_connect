<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Annonce;
use App\Entity\Boutique;
use App\Form\SearchType;
use App\Repository\FavoryRepository;
use App\Repository\AnnonceRepository;
use App\Repository\BoutiqueRepository;
use App\Repository\CategoryRepository;
use App\Repository\ImagesHeroRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use function Symfony\Config\Security\FirewallConfig\name;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(BoutiqueRepository $boutiqueRepository, CategoryRepository $categoryRepository, AnnonceRepository $annonceRepository, ImagesHeroRepository $imagesHeroRepository): Response
    {
        $categories     = $categoryRepository->findAll();
        $annonces       = $annonceRepository->findBy(['actif' => 1], ['created_at' => 'DESC'], 4);
        $imagesHero     = $imagesHeroRepository->findAll();
        $user           = $this->getUser();
        $allBoutiques   = $boutiqueRepository->findAll();
        $boutiquesInfos = [];
        foreach ($allBoutiques as $boutique){
            $boutiquesInfos[] = [ $boutique->getTitle() , $boutique->getLat() , $boutique->getLng() , $boutique->getId() ];
        }

        return $this->render('front/homepage.html.twig', [
            'categories'   => $categories,
            'annonces'     => $annonces,
            'imagesHero'   => $imagesHero,
            'user'         => $user,
            'boutiquesInfos' => $boutiquesInfos,
        ]);
    }
    
    #[Route('/recherche', name: 'app_annonce_recherche', methods: ['GET'])]
    public function recherche(AnnonceRepository $annonceRepository,Request $request): Response
    {
        $data       = new SearchData();
        $data->page = $request->get('page',1);
        $recherche  = $this->createForm(SearchType::class,$data);
        $recherche->handleRequest($request);
        [$min, $max] = $annonceRepository->findMinMax($data);
        $annonces = $annonceRepository->findBySearch($data);
//        if ($request->isXmlHttpRequest()){
//            if(!$request->get('category') ){
//                return new JsonResponse([
//                    'content' => $this->renderView('front/annonce/_annonces.html.twig', ['annonces' => $annonces]),
//                    'sort' => $this->renderView('front/annonce/_sort.html.twig', ['annonces' => $annonces]),
//                ]);
//            }
//        }
        return $this->render('front/annonce/recherche_annonce.html.twig', [
            'annonces'  => $annonces,
            'recherche' => $recherche->createView(),
            'min'       => $min,
            'max'       => $max,
        ]);
    }

    #[Route('/public/{id}/{id_annonce}', name: 'view_boutique_annonce_focus', methods: ['GET'])]
    public function oneBoutiqueFocusAnnonce( Boutique $boutique, $id_annonce, AnnonceRepository $annonceRepository, FavoryRepository $favoryRepository)
    {
        // L'annonce existe elle?
        if (!empty($id_annonce)) {
            $annonce = $annonceRepository->find($id_annonce);
            if (!empty($annonce)){
                // L'annnonce est elle bien associée à la boutique?
                if ($annonce->getBoutique() !== $boutique)
                {
                    return $this->redirectToRoute('404',[], Response::HTTP_SEE_OTHER);
                }
            }else{
                return $this->redirectToRoute('404',[], Response::HTTP_SEE_OTHER);
            }
        }

        // La boutique affichée est elle celle de l'utilisateur connecté?
        $user          = $this->getUser();
        $me            = $boutique->getUser();
        $notMyBoutique = true;
        if($user){
            if ($me->getId() === $user->getId() ){
                $notMyBoutique = false;
            }
        }

        // Favoris
        $favory        = '';
        $alreadyFavory = $favoryRepository->findOneBy(['user'=> $this->getUser(), 'boutique' => $boutique]);
        if (!empty($alreadyFavory)){
            $favory = 'favory_active';
        }

        $annonces = $annonceRepository->getActifAnnoncesBoutique($boutique->getId(), $id_annonce);
        $annonce  = null;

        return $this->render(
            'front/boutique/viewboutique.html.twig',
            [
                'annonce'       => $annonce,
                'annonces'      => $annonces,
                'boutique'      => $boutique,
                'notMyboutique' => $notMyBoutique,
                'favory'        => $favory
            ]
        );
    }

    #[Route('/mention-legale', name: 'mention_legale')]
    public function mentionLegale(): Response
    {
        return $this->render('front/mention.html.twig');
    }
}
