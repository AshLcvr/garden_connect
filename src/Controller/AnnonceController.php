<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use App\Repository\CategoryRepository;
use App\Repository\SubcategoryRepository;
use App\Service\UploadImage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/annonce')]
class AnnonceController extends AbstractController
{
    #[Route('/recherche', name: 'app_annonce_recherche', methods: ['GET'])]
    public function recherche(AnnonceRepository $annonceRepository): Response
    {
        $annonces = $annonceRepository->findAll();

        return $this->render('front/annonce/recherche_annonce.html.twig', [
            'annonces' => $annonces,
        ]);
    }



    #[Route('/', name: 'app_annonce_index', methods: ['GET'])]
    public function index(AnnonceRepository $annonceRepository): Response
    {
        $user = $this->getUser();
        $boutiques = $user->getBoutiques();
        $boutique = $boutiques[0];
        $annonces = $boutique->getAnnonces();
//        $annonces = $annonceRepository->findBy(['annonce'=>$annonce->getId()]);

        return $this->render('front/annonce/index_annonce.html.twig', [
            'annonces' => $annonces,
        ]);
    }

    #[Route('/new', name: 'app_annonce_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SubcategoryRepository $subcategoryRepository ,AnnonceRepository $annonceRepository, UploadImage $uploadImage, CategoryRepository $categoryRepository): Response
    {
        $user = $this->getUser();
        $boutiques = $user->getBoutiques();
        $boutique = $boutiques[0];

        $annonce = new Annonce();
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $annonce->setBoutique($boutique);
            $annonce->setActif(1);
            $annonce->setCreatedAt(new \DateTimeImmutable());
            $annonceRepository->add($annonce, true);
            $annonceImage = $form->get('upload')->getData();
            if (count($annonceImage) <= 4 || empty($annonceImage)) {
                $uploadImage->uploadAnnonce($annonceImage, $annonce->getId());
            }else{
                $this->addFlash('failure','4 photos max !');
                return $this->redirectToRoute('app_annonce_new', [], Response::HTTP_SEE_OTHER);
            }
            return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/annonce/new_annonce.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    #[Route('/', name: 'app_annonce_show', methods: ['GET'])]
    public function show(Annonce $annonce): Response
    {
        $user = $this->getUser();
        $annonces = $user->getannonces();
        $annonce = $annonces[0];
        $annonces = $annonce->getAnnonces();

        return $this->render('front/annonce/detail_annonce.html.twig', [
            'annonce' => $annonce,
            'annonces' => $annonces,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_annonce_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Annonce $annonce, AnnonceRepository $annonceRepository, UploadImage $uploadImage): Response
    {
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $annonce->setModifiedAt(new \DateTimeImmutable());
            $annonceRepository->add($annonce, true);
            $annonceImage = $form->get('upload')->getData();
            if (count($annonceImage) <= 4 || empty($annonceImage)) {
                $uploadImage->uploadAnnonce($annonceImage, $annonce->getId());
            }else{
                $this->addFlash('failure','4 photos max !');
                return $this->redirectToRoute('app_annonce_new', [], Response::HTTP_SEE_OTHER);
            }
            return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/annonce/edit_annonce.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_annonce_delete', methods: ['POST'])]
    public function delete(Request $request, Annonce $annonce, AnnonceRepository $annonceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$annonce->getId(), $request->request->get('_token'))) {
            $annonceRepository->remove($annonce, true);
        }

        return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
    }
}
