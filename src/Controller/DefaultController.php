<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use App\Repository\AnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(CategoriesRepository $categoriesRepository, AnnonceRepository $annonceRepository): Response
    {
        $categories = $categoriesRepository->findBy([
            'parent' => null
        ]);
        $annonces = $annonceRepository->findAll();
        // $imageHero = 

        return $this->render('front/homepage.html.twig',[
            'categories' => $categories,
            'annonces' => $annonces,
        ]);
    }

    
}
