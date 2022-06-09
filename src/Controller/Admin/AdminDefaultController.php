<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Annonce;
use App\Entity\ImagesHero;
use App\Form\ImagesHeroType;
use App\Service\UploadImage;
use App\Repository\UserRepository;
use App\Repository\AnnonceRepository;
use App\Repository\ImagesHeroRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
class AdminDefaultController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig', [
//            'controller_name' => 'AdminDefaultController',
        ]);
    }

    #[Route('/users', name: 'all-users')]
    public function allUsers(UserRepository $userRepository): Response
    {
        $usersActif = $userRepository->findBy([
            'actif' => true
        ]);
        $usersInactif = $userRepository->findBy([
            'actif' => false
        ]);

        return $this->render('admin/users.html.twig',[
            'usersActif' => $usersActif,
            'usersInactif' => $usersInactif,
        ]);
    }

    #[Route('/users/disable/{id}', name: 'disable-user')]
    public function disableUser(User $user, UserRepository $userRepository): Response
    {
        $user->setActif(false);
        $userRepository->add($user, true);
        return $this->redirectToRoute('all-users', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/users/active/{id}', name: 'active-user')]
    public function activeUser(User $user, UserRepository $userRepository): Response
    {
        $user->setActif(true);
        $userRepository->add($user, true);
        return $this->redirectToRoute('all-users', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/annonce', name: 'all-annonce')]
    public function annoce(AnnonceRepository $annonceRepository): Response
    {
        $annonces = $annonceRepository->findAll();

        return $this->render('admin/annonces.html.twig',[
            'annonces' => $annonces,
        ]);
    }
    #[Route('/annonce/{id}', name: 'details-annonce')]
    public function detailsAnnoce(Annonce $annonce): Response
    {
        return $this->render('admin/details-annonce.html.twig',[
            'annonce' => $annonce,
        ]);
    }
    #[Route('/annonce/disable/{id}', name: 'disable-annonce')]
    public function disableAnnonce(Annonce $annonce, AnnonceRepository $annonceRepository): Response
    {
        $annonce->setActif(false);
        $annonceRepository->add($annonce, true);
        return $this->redirectToRoute('details-annonce', ['id' => $annonce->getId()], Response::HTTP_SEE_OTHER);
    }
    #[Route('/annonce/active/{id}', name: 'active-annonce')]
    public function activeAnnonce(Annonce $annonce, AnnonceRepository $annonceRepository): Response
    {
        $annonce->setActif(true);
        $annonceRepository->add($annonce, true);
        return $this->redirectToRoute('details-annonce', ['id' => $annonce->getId()], Response::HTTP_SEE_OTHER);
    }
    #[Route('/hero', name: 'images_hero')]
    public function imagesHero(Request $request, ImagesHeroRepository $imagesHeroRepository, UploadImage $uploadImage): Response
    {
        $form = $this->createForm(ImagesHeroType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageHero = $form->get('upload')->getData();
            // dd($imageHero);
            $uploadImage->uploadHero($imageHero);
        }

        return $this->render('admin/hero.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
