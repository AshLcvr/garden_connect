<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Form\SearchType;
use App\Repository\AnnonceRepository;
use App\Repository\CategoryRepository;
use App\Repository\ImagesHeroRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(CategoryRepository $categoryRepository, AnnonceRepository $annonceRepository, ImagesHeroRepository $imagesHeroRepository): Response
    {
        $categories = $categoryRepository->findAll();
        $annonces = $annonceRepository->findBy(['actif'=> 1],['created_at' => 'DESC'], 4);
        $imagesHero = $imagesHeroRepository->findAll();
        $user = $this->getUser();

        return $this->render('front/homepage.html.twig',[
            'categories' => $categories,
            'annonces' => $annonces,
            'imagesHero' => $imagesHero,
            'user' => $user
        ]);
    }

    #[Route('/recherche', name: 'app_annonce_recherche', methods: ['GET'])]
    public function recherche(AnnonceRepository $annonceRepository,Request $request): Response
    {
        $data = new SearchData();
        $data->page = $request->get('page',1);
        $recherche = $this->createForm(SearchType::class,$data);
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
            'annonces' => $annonces,
            'recherche' => $recherche->createView(),
            'min' => $min,
            'max' => $max,
        ]);
    }

    #[Route('/mention-legale', name: 'mention_legale')]
    public function mentionLegale(): Response
    {
        return $this->render('front/mention.html.twig');
    }
}
