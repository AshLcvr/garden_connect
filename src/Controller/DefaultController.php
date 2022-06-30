<?php

namespace App\Controller;

use App\Repository\AnnonceRepository;
use App\Repository\CategoryRepository;
use App\Repository\ImagesHeroRepository;
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
    #[Route('/mention-legale', name: 'mention_legale')]
    public function mentionLegale(): Response
    {
        return $this->render('front/mention.html.twig');
    }
    #[Route('/test', name: 'test')]
    public function test(): Response
    {
        return $this->render('front/test.html.twig');
    }
}
