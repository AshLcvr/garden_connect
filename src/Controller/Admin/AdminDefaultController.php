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
use App\Repository\BoutiqueRepository;
use App\Entity\Boutique;
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


    // users
    #[Route('/users', name: 'all-users')]
    public function allUsers(UserRepository $userRepository): Response
    {
        $usersActif = $userRepository->findBy([
            'actif' => true
        ]);
        $usersInactif = $userRepository->findBy([
            'actif' => false
        ]);

        return $this->render('admin/user/users.html.twig',[
            'usersActif' => $usersActif,
            'usersInactif' => $usersInactif,
        ]);
    }
    #[Route('/user/{id}', name: 'details-user')]
    public function detailsUser(User $user): Response
    {
        return $this->render('admin/user/details-user.html.twig',[
            'user' => $user,
        ]);
    }
    #[Route('/users/disable/{id}', name: 'disable-user')]
    public function disableUser(User $user, UserRepository $userRepository): Response
    {
        $user->setActif(false);
        $userRepository->add($user, true);
        return $this->redirectToRoute('details-user', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
    }
    #[Route('/users/active/{id}', name: 'active-user')]
    public function activeUser(User $user, UserRepository $userRepository): Response
    {
        $user->setActif(true);
        $userRepository->add($user, true);
        return $this->redirectToRoute('details-user', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
    }


    // annonces
    #[Route('/annonce', name: 'all-annonces')]
    public function annonce(AnnonceRepository $annonceRepository): Response
    {
        $annoncesActif = $annonceRepository->findBy([
            'actif' => true
        ]);
        $annoncesInactif = $annonceRepository->findBy([
            'actif' => false
        ]);

        return $this->render('admin/annonce/annonces.html.twig',[
            'annoncesActif' => $annoncesActif,
            'annoncesInactif' => $annoncesInactif
        ]);
    }
    #[Route('/annonce/{id}', name: 'details-annonce')]
    public function detailsAnnonce(Annonce $annonce): Response
    {
        return $this->render('admin/annonce/details-annonce.html.twig',[
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

    // boutiques
    #[Route('/boutique', name: 'all-boutiques')]
    public function boutique(BoutiqueRepository $boutiqueRepository): Response
    {
        $boutiquesActif = $boutiqueRepository->findBy([
            'actif' => true
        ]);
        $boutiquesInactif = $boutiqueRepository->findBy([
            'actif' => false
        ]);

        return $this->render('admin/boutique/boutiques.html.twig',[
            'boutiquesActif' => $boutiquesActif,
            'boutiquesInactif' => $boutiquesInactif
        ]);
    }
    #[Route('/boutique/{id}', name: 'details-boutique')]
    public function detailsBoutique(Boutique $boutique): Response
    {
        return $this->render('admin/boutique/details-boutique.html.twig',[
            'boutique' => $boutique,
        ]);
    }
    #[Route('/boutique/disable/{id}', name: 'disable-boutique')]
    public function disableBoutique(Boutique $boutique, BoutiqueRepository $boutiqueRepository): Response
    {
        $boutique->setActif(false);
        $boutiqueRepository->add($boutique, true);
        return $this->redirectToRoute('details-boutique', ['id' => $boutique->getId()], Response::HTTP_SEE_OTHER);
    }
    #[Route('/boutique/active/{id}', name: 'active-boutique')]
    public function activeBoutique(Boutique $boutique, BoutiqueRepository $boutiqueRepository): Response
    {
        $boutique->setActif(true);
        $boutiqueRepository->add($boutique, true);
        return $this->redirectToRoute('details-boutique', ['id' => $boutique->getId()], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/hero', name: 'images_hero')]
    public function imagesHero(Request $request, ImagesHeroRepository $imagesHeroRepository, UploadImage $uploadImage): Response
    {
        $imagesHero = $imagesHeroRepository->findAll();
        $form = $this->createForm(ImagesHeroType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageHero = $form->get('upload')->getData();
            // dd($imageHero);
            $uploadImage->uploadHero($imageHero);
        }

        return $this->render('admin/hero.html.twig', [
            'form' => $form->createView(),
            'imagesHero' => $imagesHero
        ]);
    }
}
