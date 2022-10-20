<?php

namespace App\Controller\Admin;

use App\Entity\Avis;
use App\Entity\User;
use App\Entity\Annonce;
use App\Entity\Boutique;
use App\Entity\ImagesHero;
use App\Form\EditProfilType;
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
            return $this->redirectToRoute('admin_view_profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/profil/edit_profil.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/users/actifs', name: 'all_users_actifs')]
    public function allUsersActifs(Request $request, UserRepository $userRepository, PaginatorInterface $paginator): Response
    {
        
        $usersActif = $userRepository->findBy([
            'actif' => true
        ]);

        $usersActif = $this->maPagination($usersActif, $paginator, $request, 5);

        return $this->render('admin/user/users_actifs.html.twig',[
            'usersActif' => $usersActif
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
    
    #[Route('/hero', name: 'images_hero')]
    public function imagesHero(Request $request, ImagesHeroRepository $imagesHeroRepository): Response
    {
        $imagesHero = $imagesHeroRepository->findBy([],['position'=>'ASC']);

        return $this->render('admin/diapo_home/hero.html.twig', [
            'imagesHero' => $imagesHero
        ]);
    }

    #[Route('/hero/new', name: 'new_images_hero', methods: ['GET', 'POST'])]
    public function newImagesHero(Request $request, ImagesHeroRepository $imagesHeroRepository, UploadImage $uploadImage): Response
    {
        $nbImagesHero = count($imagesHeroRepository->findAll())+1;
        $image        = new ImagesHero();

        $form         = $this->createForm(ImagesHeroType::class, $image);
        $form->get('position')->setData(1);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $image->setPosition($form->get('position')->getData());

            if(!empty($form->get('upload')->getData())){
                $imageHero[] = $form->get('upload')->getData();
                $uploadImage->uploadAndResizeImage($imageHero, $image);
                $imagesHeroRepository->add($image,true);
            }else{
                $this->addFlash('failure','Ajoutez une image');
                return $this->redirectToRoute('new_images_hero', [], Response::HTTP_SEE_OTHER);
            }
            return $this->redirectToRoute('images_hero', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/diapo_home/new.html.twig', [
            'form'         => $form->createView(),
            'nbImagesHero' => $nbImagesHero
        ]);
    }

    #[Route('/hero/edit/{id}', name: 'edit_images_hero', methods: ['GET', 'POST'])]
    public function editImagesHero(Request $request, ImagesHero $image, UploadImage $uploadImage, ImagesHeroRepository $imagesHeroRepository): Response
    {
        $nbImagesHero = count($imagesHeroRepository->findAll());
        $actualImagePosition = $image->getPosition();

        $form = $this->createForm(ImagesHeroType::class, $image);
        $form->get('position')->setData($image->getPosition());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            if ($form->get('position')->getData() != $actualImagePosition) {
              $image->setPosition($form->get('position')->getData());
            }
            if ($form->get('upload')->getData()) {
                $imageHero[] = $form->get('upload')->getData();
                $uploadImage->uploadAndResizeImage($imageHero, $image);
            }
            else {
                $imagesHeroRepository->add($image, true);
            }
            
            return $this->redirectToRoute('images_hero', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/diapo_home/edit.html.twig', [
            'form'         => $form->createView(),
            'nbImagesHero' => $nbImagesHero
        ]);
    }

    #[Route('/hero/sort/{id}/{position}', name: 'admin_imageshero_sort', methods: ['GET'])]
    public function sortAction(ImagesHero $imagesHero, Request $request, ImagesHeroRepository $imagesHeroRepository)
    {
        $position = $request->attributes->get('position');
        $imagesHero->setPosition($position);
        $imagesHeroRepository->add($imagesHero,true);

        return $this->imagesHero($request,$imagesHeroRepository);
    }

    #[Route('/hero/delete/{id}', name: 'delete_images_hero', methods: ['POST'])]
    public function deleteImagesHero(Request $request, ImagesHero $imageHero, ImagesHeroRepository $imagesHeroRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$imageHero->getId(), $request->request->get('_token'))) {
            $imagesHeroRepository->remove($imageHero, true);
        }

        return $this->redirectToRoute('images_hero', [], Response::HTTP_SEE_OTHER);
    }

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
