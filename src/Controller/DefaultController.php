<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\User;
use App\Entity\ImagesHero;
use App\Form\AvisFormType;
use App\Form\EditProfilType;
use App\Service\UploadImage;
use App\Repository\AvisRepository;
use App\Repository\UserRepository;
use App\Repository\AnnonceRepository;
use App\Repository\CategoryRepository;
use App\Repository\ImagesHeroRepository;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/profil', name: 'profil')]
    public function profil()
    {
        $user = $this->getUser();
        return $this->render('front/profil/profil.html.twig', [
            'user' => $user
        ]);
    }
    #[Route('/profil/{id}', name: 'edit_profil', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
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


        return $this->renderForm('front/profil/edit_profil.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    #[Route('/profil/avis', name: 'profil_avis')]
    public function avis_profil()
    {
        $user = $this->getUser();
        $avis = $user->getAvis();
        $mesAvis = [];
        
        $globalRating = [];
        $totalGlobalRating = [];
        $numberAvis = [];
        $totalNumberAvis = [];

        if ($avis){
            foreach ($avis as $key => $avi) {
                $mesAvis[] = $avi;
                $numberAvis = count($avi->getBoutique()->getAvis());
                $totalNumberAvis[$avi->getBoutique()->getId()] = count($avi->getBoutique()->getAvis());
                foreach ($avi->getBoutique()->getAvis() as $key => $value) {
                    $globalRating[] = $value->getRating();
                }
                $totalGlobalRating[$avi->getBoutique()->getId()] = round(array_sum($globalRating)/$numberAvis);
                $globalRating = [];
            }
        }else{
            $totalGlobalRating = 0;
        }
        usort($mesAvis, function(Avis $a, Avis $b){
            return $a->getCreatedAt()>$b->getCreatedAt()?-1:1;
        });
        return $this->render('front/profil/avis/avis.html.twig', [
            'user' => $user,
            'mesAvis' => $mesAvis,
            'totalGlobalRating' => $totalGlobalRating,
            'totalNumberAvis' => $totalNumberAvis
        ]);
    }
    #[Route('/profil/avis/{id}', name: 'profil_avis_edit')]
    public function edit_avis(Request $request, Avis $avis, AvisRepository $avisRepository)
    {
        $form = $this->createForm(AvisFormType::class, $avis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avisRepository->add($avis, true);

            return $this->redirectToRoute('profil_avis', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/profil/avis/edit_avis.html.twig', [
            'avi' => $avis,
            'form' => $form,
        ]);
    }
}
