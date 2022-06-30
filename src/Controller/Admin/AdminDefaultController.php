<?php

namespace App\Controller\Admin;

use App\Entity\Avis;
use App\Entity\User;
use App\Entity\Annonce;
use App\Entity\Boutique;
use App\Entity\ImagesHero;
use App\Form\ImagesHeroType;
use App\Form\PaginationType;
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

#[Route('/admin')]
class AdminDefaultController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function index(UserRepository $userRepository, AnnonceRepository $annonceRepository, BoutiqueRepository $boutiqueRepository, AvisRepository $avisRepository): Response
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

        $avis = $avisRepository->findAll();
        $totalAvis = count($avis);
        $newAvis = $avisRepository->newAvis(new \DateTimeImmutable('-1 week'));
        $totalNewAvis = count($newAvis);
        
        return $this->render('admin/dashboard.html.twig', [
            'totalUsers' => $totalUsers,
            'totalNewUsers' => $totalNewUsers,
            'totalAnnonces' => $totalAnnonces,
            'totalNewAnnonces' => $totalNewAnnonces,
            'totalBoutiques' => $totalBoutiques,
            'totalNewBoutiques' => $totalNewBoutiques,
            'totalAvis' => $totalAvis,
            'totalNewAvis' => $totalNewAvis
        ]);
    }

    #[Route('/viewprofil', name: 'admin_view_profil', methods: ['GET', 'POST'])]
    public function viewProfile(TokenGeneratorInterface $tokenGenerator, UserRepository $userRepository)
    {
        $user = $this->getUser();
        $token = $tokenGenerator->generateToken();
        $user->setToken($token);
        $userRepository->add($user, true);
        return $this->render('admin/profil/view_profil.html.twig', [
                'user' => $user,
                'token' => $token
            ]
        );
    }

    #[Route('/edit/profil', name: 'admin_edit_profil', methods: ['GET', 'POST'])]
    public function editProfil(Request $request, UserRepository $userRepository, UploadImage $uploadImage)
    {
        $user = $this->getUser();
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
    #[Route('/users/actifs', name: 'all_users_actifs')]
    public function allUsersActifs(Request $request, UserRepository $userRepository, PaginatorInterface $paginator): Response
    {
        $form = $this->createForm(PaginationType::class);
        $form->handleRequest($request);

        
        $usersActif = $userRepository->findBy([
            'actif' => true
        ]);

        if ($form->isSubmitted() && $form->isValid()) {
            $form->get('pagination')->getData();
            dd($form->get('pagination')->getData());
            $usersActif = $this->maPagination($usersActif, $paginator, $request, 5);
            return $this->redirectToRoute('all_users_actifs', [], Response::HTTP_SEE_OTHER);
        }
        else {
            $usersActif = $this->maPagination($usersActif, $paginator, $request, 5);
        }

        return $this->render('admin/user/users_actifs.html.twig',[
            'usersActif' => $usersActif,
            'form' => $form
        ]);
    }
    #[Route('/users/inactifs', name: 'all_users_inactifs')]
    public function allUsersInactifs(Request $request, UserRepository $userRepository, PaginatorInterface $paginator): Response
    {
        $usersInactif = $userRepository->findBy([
            'actif' => false
        ]);
        $usersInactif = $this->maPagination($usersInactif, $paginator, $request, 5);

        return $this->render('admin/user/users_inactifs.html.twig',[
            'usersInactif' => $usersInactif,
        ]);
    }

    #[Route('/user/details/{id}', name: 'details-user', requirements: ['id' => '\d+'])]
    public function detailsUser(User $user): Response
    {
        return $this->render('admin/user/details-user.html.twig',[
            'user' => $user,
        ]);
    }
    #[Route('/users/active/{id}', name: 'toggle_active_user', requirements: ['id' => '\d+'])]
    public function toggleActiveUser(User $user, UserRepository $userRepository): Response
    {
        if ($user->isActif()) {
            $user->setActif(false);
        }else{
            $user->setActif(true);
        }
        $userRepository->add($user, true);
        return $this->redirectToRoute('details-user', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
    }


    // annonces
    #[Route('/annonces/actives', name: 'all_annonces_actives')]
    public function allAnnonceActives(Request $request, AnnonceRepository $annonceRepository, PaginatorInterface $paginator): Response
    {
        $annoncesActif = $annonceRepository->findBy([
            'actif' => true
        ]);
        $annoncesActif = $this->maPagination($annoncesActif, $paginator, $request, 5);

        return $this->render('admin/annonce/annonces_actives.html.twig',[
            'annoncesActif' => $annoncesActif
        ]);
    }
    #[Route('/annonces/inactives', name: 'all_annonces_inactives')]
    public function allAnnonceInactives(Request $request, AnnonceRepository $annonceRepository, PaginatorInterface $paginator): Response
    {
        $annoncesInactif = $annonceRepository->findBy([
            'actif' => false
        ]);
        $annoncesInactif = $this->maPagination($annoncesInactif, $paginator, $request, 5);

        return $this->render('admin/annonce/annonces_inactives.html.twig',[
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
    #[Route('/annonce/active/{id}', name: 'toggle_active_annonce')]
    public function toggleActiveAnnonce(Annonce $annonce, AnnonceRepository $annonceRepository): Response
    {
        if ($annonce->isActif()) {
            $annonce->setActif(false);
        }else{
            $annonce->setActif(true);
        }
        $annonceRepository->add($annonce, true);
        return $this->redirectToRoute('details-annonce', ['id' => $annonce->getId()], Response::HTTP_SEE_OTHER);
    }

    // boutiques
    #[Route('/boutiques/actives', name: 'all_boutiques_actives')]
    public function allBoutiquesActives(Request $request, BoutiqueRepository $boutiqueRepository, PaginatorInterface $paginator): Response
    {
        $boutiquesActif = $boutiqueRepository->findBy([
            'actif' => true
        ]);
        $boutiquesActif = $this->maPagination($boutiquesActif, $paginator, $request, 5);

        return $this->render('admin/boutique/boutiques_actives.html.twig',[
            'boutiquesActif' => $boutiquesActif
        ]);
    }
    #[Route('/boutiques/inactives', name: 'all_boutiques_inactives')]
    public function allBoutiquesInactives(Request $request, BoutiqueRepository $boutiqueRepository, PaginatorInterface $paginator): Response
    {
        $boutiquesInactif = $boutiqueRepository->findBy([
            'actif' => false
        ]);
        $boutiquesInactif = $this->maPagination($boutiquesInactif, $paginator, $request, 5);

        return $this->render('admin/boutique/boutiques_inactives.html.twig',[
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
    #[Route('/boutique/active/{id}', name: 'toggle_active_boutique')]
    public function toggleActiveBoutique(Boutique $boutique, BoutiqueRepository $boutiqueRepository): Response
    {
        if ($boutique->isActif()) {
            $boutique->setActif(false);
        }else{
            $boutique->setActif(true);
        }
        $boutiqueRepository->add($boutique, true);
        return $this->redirectToRoute('details-boutique', ['id' => $boutique->getId()], Response::HTTP_SEE_OTHER);
    }
    
    // diapo page home
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

    // avis
    #[Route('/avis/actifs', name: 'all_avis_actifs')]
    public function allAvisActifs(Request $request, AvisRepository $avisRepository, PaginatorInterface $paginator): Response
    {
        $avisActif = $avisRepository->findBy([
            'actif' => true
        ]);
        $avisActif = $this->maPagination($avisActif, $paginator, $request, 5);

        return $this->render('admin/avis/avis_actifs.html.twig',[
            'avisActif' => $avisActif
        ]);
    }
    #[Route('/avis/inactifs', name: 'all_avis_inactifs')]
    public function allAvisInactifs(Request $request, AvisRepository $avisRepository, PaginatorInterface $paginator): Response
    {
        $avisInactif = $avisRepository->findBy([
            'actif' => false
        ]);
        $avisInactif = $this->maPagination($avisInactif, $paginator, $request, 5);

        return $this->render('admin/avis/avis_inactifs.html.twig',[
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
    #[Route('/avis/active/{id}', name: 'toggle_active_avis')]
    public function toggleActiveAvis(Avis $avis, AvisRepository $avisRepository): Response
    {
        if ($avis->isActif()) {
            $avis->setActif(false);
        }else{
            $avis->setActif(true);
        }
        $avisRepository->add($avis, true);
        return $this->redirectToRoute('details-avis', ['id' => $avis->getId()], Response::HTTP_SEE_OTHER);
    }
}
