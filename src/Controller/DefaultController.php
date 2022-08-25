<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Annonce;
use App\Entity\Avis;
use App\Entity\Boutique;
use App\Entity\Favory;
use App\Form\AvisFormType;
use App\Form\SearchType;
use App\Repository\AvisRepository;
use App\Service\CallApi;
use Faker\Provider\Lorem;
use App\Repository\FavoryRepository;
use App\Repository\MesureRepository;
use App\Repository\AnnonceRepository;
use App\Repository\BoutiqueRepository;
use App\Repository\CategoryRepository;
use App\Repository\ImagesHeroRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Config\Security\FirewallConfig\name;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function homepage(BoutiqueRepository $boutiqueRepository, CategoryRepository $categoryRepository, AnnonceRepository $annonceRepository, ImagesHeroRepository $imagesHeroRepository): Response
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

        $annonces = $annonceRepository->getActifAnnoncesBoutique($boutique->getId(), $id_annonce);

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


        return $this->render(
            'front/boutique/viewboutique.html.twig',
            [
                'annonce'       => $annonce,
                'annonces'      => $annonces,
                'boutique'      => $boutique,
                'notMyboutique' => $notMyBoutique,
                'favory'        => $favory,
                'user'          => $boutique->getUser()
            ]
        );
    }

    #[Route('/public/{id}', name: 'view_boutique')]
    public function oneBoutique(Boutique $boutique, AnnonceRepository $annonceRepository, AvisRepository $avisRepository, Request $request, FavoryRepository $favoryRepository)
    {
        $user = $this->getUser();
        $annonce = null;
        $annonces = $annonceRepository->findBy([
            'boutique' => $boutique->getId(),
            'actif' => true
        ]);

        // Détection si la page actuelle est notre boutique
        $notMyboutique = true;
        $me = $boutique->getUser();
        if($user){
            if ($me->getId() === $user->getId() ){
                $notMyboutique = false;
            }
        }

        // Favoris
        $favory = '';
        $alreadyFavory = $favoryRepository->findOneBy(['user'=> $this->getUser(), 'boutique' => $boutique]);
        if (!empty($alreadyFavory)){
            $favory = 'favory_active';
        }

        /// Avis
        $avis = $avisRepository->findBy(['boutique'=>$boutique]);
        if ($avis){
            $numberAvis = count($avis);
            $total = [];
            foreach ($avis as  $avi){
                $total[] = $avi->getRating();
            }
            $globalRating = array_sum($total)/$numberAvis;
        }else{
            $globalRating = 0;
        }
        $avisAlreadyExist = false;

        $newAvis = new Avis();
        $form = $this->createForm(AvisFormType::class,$newAvis);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $rating = $form->get('rating')->getData();
            $newAvis
                ->setRating($rating)
                ->setUser($this->getUser())
                ->setBoutique($boutique)
                ->setActif(true)
                ->setCreatedAt(new \DateTimeImmutable());
            $avisRepository->add($newAvis, true);

            return $this->redirectToRoute('view_boutique', ['id' => $boutique->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render(
            'front/boutique/viewboutique.html.twig', [
                'boutique'         => $boutique,
                'notMyboutique'    => $notMyboutique,
                'annonces'         => $annonces,
                'annonce'          => $annonce,
                'avis'             => $avis,
                'avisAlreadyExist' => $avisAlreadyExist,
                'globalRating'     => $globalRating,
                'form'             => $form->createView(),
                'favory'           => $favory,
                'user'          => $boutique->getUser()
            ]
        );
    }

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
}
