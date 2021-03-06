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
        $annonces = $annonceRepository->findBy(['actif'=> 1],['created_at' => 'DESC'], 4);
        $imagesHero = $imagesHeroRepository->findAll();

        return $this->render('front/homepage.html.twig',[
            'categories' => $categories,
            'annonces' => $annonces,
            'imagesHero' => $imagesHero
        ]);
    }

    #[Route('/mention-legale', name: 'mention_legale')]
    public function mentionLegale(): Response
    {
        return $this->render('front/mention.html.twig');
    }


    public function notification(): Response
    {
        
        return $this->render('front/mention.html.twig');
    }
}
