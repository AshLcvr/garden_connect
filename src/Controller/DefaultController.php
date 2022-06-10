<?php

namespace App\Controller;

use App\Repository\AnnonceRepository;
use App\Repository\CategoryRepository;
use App\Entity\ImagesHero;
use App\Repository\ImagesHeroRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(CategoryRepository $categoryRepository, AnnonceRepository $annonceRepository, ImagesHeroRepository $imagesHeroRepository): Response
    {
        $categories = $categoryRepository->findAll();
        $annonces = $annonceRepository->findBy([],['created_at' => 'DESC'], 4);
        $imagesHero = $imagesHeroRepository->findAll();
        // dd($imagesHero);

        return $this->render('front/homepage.html.twig',[
            'categories' => $categories,
            'annonces' => $annonces,
            'imagesHero' => $imagesHero
        ]);
    }
}
