<?php

namespace App\Controller\Boutique;

use App\Entity\Avis;
use App\Entity\Boutique;
use App\Form\AvisFormType;
use App\Form\BoutiqueType;
use App\Form\EditProfilType;
use App\Repository\AvisRepository;
use App\Repository\FavoryRepository;
use App\Service\UploadImage;
use App\Service\CallApi;
use App\Entity\ImagesBoutique;
use App\Repository\UserRepository;
use App\Repository\AnnonceRepository;
use App\Repository\BoutiqueRepository;
use App\Repository\ImagesBoutiqueRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authenticator\FormLoginAuthenticator;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

#[Route('/boutique')]
class BoutiqueController extends AbstractController
{
    #[Route('/', name: 'app_boutique_index', methods: ['GET'])]
    public function indexBoutique(): Response
    {
        $user = $this->getUser();
        $boutiques = $user->getBoutiques();
        $boutique = $boutiques[0];

        return $this->render('front/boutique/index_boutique.html.twig', [
            'boutiques' => $boutiques,
            'boutique' => $boutique,
        ]);
    }

    #[Route('/nouvelleboutique', name: 'app_boutique_new', methods: ['GET', 'POST'])]
    public function newBoutique(Request $request, BoutiqueRepository $boutiqueRepository, UploadImage $uploadImage, ImagesBoutiqueRepository $imagesBoutiqueRepository, UserRepository $userRepository, FormLoginAuthenticator $formLoginAuthenticator, UserAuthenticatorInterface $userAuthenticator, CallApi $callApi): Response
    {
        $user = $this->getUser();
        $boutique = new Boutique();
        $form = $this->createForm(BoutiqueType::class, $boutique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $boutique->setUser($user);
            $formatedTel =  $form->get('indicatif')->getData() . $form->get('telephone')->getData();
            $boutique->setTelephone($formatedTel);
            $boutique->setCoordinates($callApi->getBoutiqueAdressCoordinates($form->get('postcode')->getData(),$form->get('city')->getData(),$form->get('adress')->getData()));
            $boutique->setActif(true);
            $boutique->setCreatedAt(new \DateTimeImmutable());
            $boutiqueRepository->add($boutique, true);
            $boutiqueImage = $form->get('upload')->getData();
            if (count($boutiqueImage) <= 4 || empty($boutiqueImage)) {
                if (empty($boutiqueImage)) {
                    $imageDefault = new ImagesBoutique();
                    $imageDefault->setTitle('imageBoutiqueDefault.jpg');
                    $imageDefault->setBoutique($boutique);
                    $imagesBoutiqueRepository->add($imageDefault, true);
                } else {
                    $uploadImage->uploadBoutique($boutiqueImage, $boutique->getId());
                }
                $user->setRoles(['ROLE_VENDEUR']);
                $userRepository->add($user, true);
                $userAuthenticator->authenticateUser($user, $formLoginAuthenticator, $request);
                return $this->redirectToRoute('app_boutique_index', [], Response::HTTP_SEE_OTHER);
            }else{
                $this->addFlash('failure','4 photos max !');
                return $this->redirectToRoute('app_boutique_new', [], Response::HTTP_SEE_OTHER);
            }

        }

        return $this->renderForm('front/boutique/new_boutique.html.twig', [
            'boutique' => $boutique,
            'form' => $form,
        ]);
    }

    #[Route('/detail', name: 'app_boutique_detail', methods: ['GET', 'POST'])]
    public function detailBoutique(): Response
    {
        $user = $this->getUser();
        $boutiques = $user->getBoutiques();
        $boutique = $boutiques[0];

        return $this->render('front/boutique/detail_boutique.html.twig', [
            'boutique' => $boutique,
        ]);
    }

    #[Route('/boutique/{id}/edit', name: 'app_boutique_edit', methods: ['GET', 'POST'])]
    public function editBoutique(Request $request, Boutique $boutique, BoutiqueRepository $boutiqueRepository, UploadImage $uploadImage, CallApi $callApi): Response
    {
        $security = $this->security($boutique, $this->getUser()->getBoutiques());
        $form = $this->createForm(BoutiqueType::class, $boutique);
        $form->get('search')->setData($boutique->getCity().' ('.$boutique->getPostcode().')');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $boutique->setUser($this->getUser());
            $boutique->setModifiedAt(new \DateTimeImmutable());
            $boutique->setCoordinates($callApi->getBoutiqueAdressCoordinates($form->get('postcode')->getData(),$form->get('city')->getData(),$form->get('adress')->getData()));
            $boutiqueRepository->add($boutique, true);

            $boutiqueImage = $form->get('upload')->getData();
            if (count($boutiqueImage) <= 4 || empty($boutiqueImage)) {
                $uploadImage->uploadBoutique($boutiqueImage, $boutique->getId());
            }else{
                $this->addFlash('failure','4 photos max !');
                return $this->redirectToRoute('app_boutique_edit', ['id'=> $boutique->getId()], Response::HTTP_SEE_OTHER);
            }
            return $this->redirectToRoute('app_boutique_detail', ['id'=> $boutique->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/boutique/edit_boutique.html.twig', [
            'boutique' => $boutique,
            'form' => $form,
        ]);
    }

    #[Route('/boutique-delete/{id}', name: 'app_boutique_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function deleteBoutique(Request $request, Boutique $boutique, BoutiqueRepository $boutiqueRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$boutique->getId(), $request->request->get('_token'))) {
            $boutiqueRepository->remove($boutique, true);
        }

        return $this->redirectToRoute('app_boutique_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/actif', name: 'app_boutique_actif', methods: ['POST', 'GET'])]
    public function toggleActif(Request $request, Boutique $boutique, BoutiqueRepository $boutiqueRepository): Response
    {
        if ($boutique->isActif()) {
            $boutique->setActif(false);
        }else{
            $boutique->setActif(true);
        }
        $boutiqueRepository->add($boutique,true);

        return $this->redirectToRoute('app_boutique_detail', ['id' => $boutique->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/public/{id}', name: 'view_boutique')]
    public function oneBoutique(Boutique $boutique, AnnonceRepository $annonceRepository, AvisRepository $avisRepository, Request $request, FavoryRepository $favoryRepository)
    {
        $user = $this->getUser();
        $annonce = null;
        $annonces = $annonceRepository->findBy([
            'boutique' => $boutique->getId(),
            'actif' => true
        ]);

        // Détection si la page actuelle est notre boutique
        $notMyboutique = true;
        $me = $boutique->getUser();
        if($user){
            if ($me->getId() === $user->getId() ){
                $notMyboutique = false;
            }
        }

        // Favoris
        $favory = '';
        $alreadyFavory = $favoryRepository->findOneBy(['user'=> $this->getUser(), 'boutique' => $boutique]);
        if (!empty($alreadyFavory)){
            $favory = 'favory_active';
        }

        /// Avis
        $avis = $avisRepository->findBy(['boutique'=>$boutique]);
        if ($avis){
            $numberAvis = count($avis);
            $total = [];
            foreach ($avis as  $avi){
                $total[] = $avi->getRating();
            }
            $globalRating = array_sum($total)/$numberAvis;
        }else{
            $globalRating = 0;
        }
        $avisAlreadyExist = false;

        $newAvis = new Avis();
        $form = $this->createForm(AvisFormType::class,$newAvis);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $rating = $form->get('rating')->getData();
            $newAvis
                ->setRating($rating)
                ->setUser($this->getUser())
                ->setBoutique($boutique)
                ->setCreatedAt(new \DateTimeImmutable());
            $avisRepository->add($newAvis, true);

            return $this->redirectToRoute('view_boutique', ['id' => $boutique->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render(
            'front/boutique/viewboutique.html.twig', [
                'boutique' => $boutique,
                'notMyboutique' => $notMyboutique,
                'annonces' => $annonces,
                'annonce'  => $annonce,
                'avis'     => $avis,
                'avisAlreadyExist' => $avisAlreadyExist,
                'globalRating' => $globalRating,
                'form'     => $form->createView(),
                'favory' => $favory
            ]
        );
    }

    #[Route('/public/{id}/{id_annonce}', name: 'view_boutique_annonce_focus', methods: ['GET'])]
    public function oneBoutiqueFocusAnnonce(AnnonceRepository $annonceRepository, Boutique $boutique,FavoryRepository $favoryRepository, $id_annonce)
    {
        $user = $this->getUser();
        $annonces = $annonceRepository->getActifAnnoncesBoutique($boutique->getId(), $id_annonce);
        $annonce = null;

        $notMyboutique = true;
        $me = $boutique->getUser();
        if($user){
            if ($me->getId() === $user->getId() ){
                $notMyboutique = false;
            }
        }

        if (!empty($id_annonce)) {
            $annonce = $annonceRepository->find($id_annonce);
        }

        // Favoris
        $favory = '';
        $alreadyFavory = $favoryRepository->findOneBy(['user'=> $this->getUser(), 'boutique' => $boutique]);
        if (!empty($alreadyFavory)){
            $favory = 'favory_active';
        }

        return $this->render(
            'front/boutique/viewboutique.html.twig',
            [
                'annonce' => $annonce,
                'annonces' => $annonces,
                'boutique' => $boutique,
                'notMyboutique' => $notMyboutique,
                'favory' => $favory
            ]
        );
    }

    #[Route('/viewprofil', name: 'boutique_view_profil', methods: ['GET', 'POST'])]
    public function viewProfile(TokenGeneratorInterface $tokenGenerator, UserRepository $userRepository)
    {
        $user = $this->getUser();
        $token = $tokenGenerator->generateToken();
        $user->setToken($token);
        $userRepository->add($user, true);
        return $this->render(
            'front/boutique/profil/view_profil.html.twig',
            [
                'user' => $user,
                'token' => $token
            ]
        );
    }

    #[Route('/edit/profil', name: 'boutique_edit_profil', methods: ['GET', 'POST'])]
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

        return $this->renderForm('front/boutique/profil/edit_profil.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}
