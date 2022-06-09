<?php

namespace App\Controller;

use App\Entity\ImagesHero;
use App\Repository\CategoriesRepository;
use App\Repository\AnnonceRepository;
use App\Repository\ImagesHeroRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(CategoriesRepository $categoriesRepository, AnnonceRepository $annonceRepository, ImagesHeroRepository $imagesHeroRepository): Response
    {
        $categories = $categoriesRepository->findBy([
            'parent' => null
        ]);
        $annonces = $annonceRepository->findAll();
        $imagesHero = $imagesHeroRepository->findAll();
        // dd($imagesHero);

        return $this->render('front/homepage.html.twig',[
            'categories' => $categories,
            'annonces' => $annonces,
            'imagesHero' => $imagesHero
        ]);
    }

    
}
