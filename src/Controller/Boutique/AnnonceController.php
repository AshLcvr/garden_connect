<?php

namespace App\Controller\Boutique;

use App\Entity\Annonce;
use App\Entity\Boutique;
use App\Form\AnnonceType;
use App\Repository\FavoryRepository;
use App\Service\UploadImage;
use App\Entity\ImagesAnnonces;
use App\Controller\DefaultController;
use App\Repository\AnnonceRepository;
use App\Repository\ImagesAnnoncesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/boutique/annonce')]
class AnnonceController extends AbstractController
{
    #[Route('/', name: 'app_annonce_index', methods: ['GET'])]
    public function indexAnnonce(): Response
    {
        $user = $this->getUser();
        $boutiques = $user->getBoutiques();
        $boutique = $boutiques[0];
        $annonces = $boutique->getAnnonces();

        return $this->render('front/annonce/index_annonce.html.twig', [
            'annonces' => $annonces,
        ]);
    }

    #[Route('/new', name: 'app_annonce_new', methods: ['GET', 'POST'])]
    public function newAnnonce(Request $request, BoutiqueController $boutiqueController,AnnonceRepository $annonceRepository, UploadImage $uploadImage, ImagesAnnoncesRepository $imagesAnnoncesRepository): Response
    {
        $boutique = $boutiqueController->getUserBoutique();

        $annonce = new Annonce();
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $annonce->setBoutique($boutique);
            $annonce->setActif(1);
            $annonce->setCreatedAt(new \DateTimeImmutable());
            $annonceRepository->add($annonce, true);
            $annonceImages = $form->get('upload')->getData();
            if (count($annonceImages) <= 4 || empty($annonceImages)) {
                if (empty($annonceImages)) {
                    $imageDefault = new ImagesAnnonces();
                    $imageDefault->setTitle('logo-sans-fond.png');
                    $imageDefault->setAnnonce($annonce);
                    $imagesAnnoncesRepository->add($imageDefault, true);
                } else {
                    $uploadImage->uploadAndResizeImage($annonceImages, $annonce);
                }
            } else {
                $this->addFlash('failure', '4 photos max !');
                return $this->redirectToRoute('app_annonce_new', [], Response::HTTP_SEE_OTHER);
            }
            return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/annonce/new_annonce.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_annonce_edit', methods: ['GET', 'POST'])]
    public function editAnnonce(Request $request, BoutiqueController $boutiqueController,Annonce $annonce, AnnonceRepository $annonceRepository, UploadImage $uploadImage): Response
    {
        $boutique = $boutiqueController->getUserBoutique();

        // L'annnonce est elle bien associée à la boutique?
        if ($annonce->getBoutique() !== $boutique) {
            return $this->redirectToRoute('403', [], Response::HTTP_SEE_OTHER);
        }

        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $annonce->setModifiedAt(new \DateTimeImmutable());
            $annonceRepository->add($annonce, true);
            $annonceImages = $form->get('upload')->getData();
            if (count($annonceImages) <= 4 || empty($annonceImages)) {
                $uploadImage->uploadAndResizeImage($annonceImages, $annonce);
            } else {
                $this->addFlash('failure', '4 photos max !');
                return $this->redirectToRoute('app_annonce_new', [], Response::HTTP_SEE_OTHER);
            }
            return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/annonce/edit_annonce.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_annonce_delete', methods: ['POST', 'GET'])]
    public function deleteAnnonce($id, Request $request, Annonce $annonce, AnnonceRepository $annonceRepository, ImagesAnnoncesRepository $imagesAnnoncesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $annonce->getId(), $request->request->get('_token'))) {
            $imagesAnnonce = $imagesAnnoncesRepository->findImagesbyAnnonceId($id);
            foreach ($imagesAnnonce as $image) {
                $imagesAnnoncesRepository->remove($image, true);
            }
            $annonceRepository->remove($annonce, true);
        }

        return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/actif/{id}', name: 'app_annonce_actif', methods: ['POST', 'GET'])]
    public function toggleActif(Annonce $annonce, AnnonceRepository $annonceRepository): Response
    {
        if ($annonce->isActif()) {
            $annonce->setActif(false);
        } else {
            $annonce->setActif(true);
        }
        $annonceRepository->add($annonce, true);

        return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
    }
}

