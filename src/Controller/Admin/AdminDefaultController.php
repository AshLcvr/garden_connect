<?php

namespace App\Controller\Admin;

use App\Entity\Avis;
use App\Entity\User;
use App\Entity\Annonce;
use App\Entity\Boutique;
use App\Entity\ImagesHero;
use App\Form\ImagesHeroType;
use App\Service\UploadImage;
use App\Repository\AvisRepository;
use App\Repository\UserRepository;
use App\Repository\AnnonceRepository;
use App\Repository\BoutiqueRepository;
use App\Repository\ImagesHeroRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

#[Route('/admin')]
class AdminDefaultController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function index(UserRepository $userRepository, AnnonceRepository $annonceRepository, BoutiqueRepository $boutiqueRepository): Response
    {
        $users = $userRepository->findAll();
        $totalUsers = count($users);
        $newUsers = $userRepository->newUsers(new \DateTimeImmutable('-1 week'));
        $totalNewUsers = count($newUsers);

        $annonces = $annonceRepository->findAll();
        $totalAnnonces = count($annonces);
        $newAnnonces = $annonceRepository->newAnnonces(new \DateTimeImmutable('-1 week'));
        $totalNewAnnonces = count($newAnnonces);

        $boutiques = $boutiqueRepository->findAll();
        $totalBoutiques = count($boutiques);
        $newBoutiques = $boutiqueRepository->newBoutiques(new \DateTimeImmutable('-1 week'));
        $totalNewBoutiques = count($newBoutiques);
        
        return $this->render('admin/dashboard.html.twig', [
            'totalUsers' => $totalUsers,
            'totalNewUsers' => $totalNewUsers,
            'totalAnnonces' => $totalAnnonces,
            'totalNewAnnonces' => $totalNewAnnonces,
            'totalBoutiques' => $totalBoutiques,
            'totalNewBoutiques' => $totalNewBoutiques,
        ]);
    }

    #[Route('/viewprofil/{id}', name: 'admin_view_profil', methods: ['GET', 'POST'])]
    public function viewProfile(User $user, TokenGeneratorInterface $tokenGenerator, UserRepository $userRepository)
    {
        $token = $tokenGenerator->generateToken();
        $user->setToken($token);
        $userRepository->add($user, true);
        return $this->render('admin/profil/view_profil.html.twig', [
                'user' => $user,
                'token' => $token
            ]
        );
    }

    #[Route('/edit/profil/{id}', name: 'admin_edit_profil', methods: ['GET', 'POST'])]
    public function editProfil(Request $request, User $user, UserRepository $userRepository, UploadImage $uploadImage)
    {
        $form = $this->createForm(EditProfilType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if($image){
                $user->setImage($uploadImage->uploadProfile($image));
            }
            $userRepository->add($user,true);
            return $this->redirectToRoute('boutique_view_profil', ['id'=> $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/profil/edit_profil.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }


    // users
    #[Route('/users', name: 'all-users')]
    public function allUsers(Request $request, UserRepository $userRepository, PaginatorInterface $paginator): Response
    {
        $usersActif = $userRepository->findBy([
            'actif' => true
        ]);
        $usersInactif = $userRepository->findBy([
            'actif' => false
        ]);
        
        $usersActif = $paginator->paginate(
            $usersActif, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            20 // Nombre de résultats par page
        );
        $usersInactif = $paginator->paginate(
            $usersInactif, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            20 // Nombre de résultats par page
        );

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
    public function annonce(Request $request, AnnonceRepository $annonceRepository, PaginatorInterface $paginator): Response
    {
        $annoncesActif = $annonceRepository->findBy([
            'actif' => true
        ]);
        $annoncesInactif = $annonceRepository->findBy([
            'actif' => false
        ]);
        $annoncesActif = $paginator->paginate(
            $annoncesActif, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            20 // Nombre de résultats par page
        );
        $annoncesInactif = $paginator->paginate(
            $annoncesInactif, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            20 // Nombre de résultats par page
        );

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
    public function boutique(Request $request, BoutiqueRepository $boutiqueRepository, PaginatorInterface $paginator): Response
    {
        $boutiquesActif = $boutiqueRepository->findBy([
            'actif' => true
        ]);
        $boutiquesInactif = $boutiqueRepository->findBy([
            'actif' => false
        ]);
        $boutiquesActif = $paginator->paginate(
            $boutiquesActif, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            20 // Nombre de résultats par page
        );
        $boutiquesInactif = $paginator->paginate(
            $boutiquesInactif, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            20 // Nombre de résultats par page
        );

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
            return $this->redirectToRoute('images_hero', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/hero.html.twig', [
            'form' => $form->createView(),
            'imagesHero' => $imagesHero
        ]);
    }
    #[Route('/hero/delete/{id}', name: 'delete_images_hero')]
    public function deleteImagesHero(ImagesHero $imageHero, ImagesHeroRepository $imagesHeroRepository): Response
    {
        $imagesHeroRepository->remove($imageHero, true);

        return $this->redirectToRoute('images_hero', [], Response::HTTP_SEE_OTHER);
    }

    // boutiques
    #[Route('/avis', name: 'all-avis')]
    public function avis(Request $request, AvisRepository $avisRepository, PaginatorInterface $paginator): Response
    {
        $avisActif = $avisRepository->findBy([
            'actif' => true
        ]);
        $avisInactif = $avisRepository->findBy([
            'actif' => false
        ]);
        
        $avisActif = $paginator->paginate(
            $avisActif, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            2 // Nombre de résultats par page
        );
        $avisInactif = $paginator->paginate(
            $avisInactif, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            20 // Nombre de résultats par page
        );

        return $this->render('admin/avis/avis.html.twig',[
            'avisActif' => $avisActif,
            'avisInactif' => $avisInactif
        ]);
    }
    #[Route('/avis/{id}', name: 'details-avis')]
    public function detailsAvis(Avis $avis): Response
    {
        return $this->render('admin/avis/details-avis.html.twig',[
            'avi' => $avis,
        ]);
    }
    #[Route('/avis/disable/{id}', name: 'disable-avis')]
    public function disableAvis(Avis $avis, AvisRepository $avisRepository): Response
    {
        $avis->setActif(false);
        $avisRepository->add($avis, true);
        return $this->redirectToRoute('details-avis', ['id' => $avis->getId()], Response::HTTP_SEE_OTHER);
    }
    #[Route('/avis/active/{id}', name: 'active-avis')]
    public function activeAvis(Avis $avis, AvisRepository $avisRepository): Response
    {
        $avis->setActif(true);
        $avisRepository->add($avis, true);
        return $this->redirectToRoute('details-avis', ['id' => $avis->getId()], Response::HTTP_SEE_OTHER);
    }

    public function configPaginator(ContainerConfigurator $configurator, $key): void
    {
        $configurator->extension('knp_paginator', [
            'page_range' => 5,                        // number of links shown in the pagination menu (e.g: you have 10 pages, a page_range of 3, on the 5th page you'll see links
            'default_options' => [
                'page_name' => $key,                // page query parameter name
                'sort_field_name' => 'sort',          // sort field query parameter name
                'sort_direction_name' => 'direction', // sort direction query parameter name
                'distinct' => true,                   // ensure distinct results, useful when ORM queries are using GROUP BY statements
                'filter_field_name' => 'filterField', // filter field query parameter name
                'filter_value_name' => 'filterValue'  // filter value query parameter name
            ],
            'template' => [
                'pagination' => '@KnpPaginator/Pagination/sliding.html.twig',     // sliding pagination controls template
                'sortable' => '@KnpPaginator/Pagination/sortable_link.html.twig', // sort link template
                'filtration' => '@KnpPaginator/Pagination/filtration.html.twig'   // filters template
            ]
        ]);
    }
}
