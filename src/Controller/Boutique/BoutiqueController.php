<?php

namespace App\Controller\Boutique;

use App\Entity\Avis;
use App\Entity\Boutique;
use App\Entity\ImagesBoutique;
use App\Form\AvisFormType;
use App\Form\BoutiqueType;
use App\Form\EditProfilType;
use App\Repository\AvisRepository;
use App\Repository\FavoryRepository;
use App\Service\UploadImage;
use App\Service\CallApi;
use App\Repository\UserRepository;
use App\Repository\AnnonceRepository;
use App\Repository\BoutiqueRepository;
use App\Repository\ImagesBoutiqueRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

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

    #[Route('/detail', name: 'app_boutique_detail', methods: ['GET', 'POST'])]
    public function detailBoutique(): Response
    {
        $boutique = $this->getUserBoutique();

        return $this->render('front/boutique/detail_boutique.html.twig', [
            'boutique' => $boutique,
        ]);
    }

    #[Route('/edit', name: 'app_boutique_edit', methods: ['GET', 'POST'])]
    public function editBoutique(Request $request, BoutiqueRepository $boutiqueRepository, ImagesBoutiqueRepository $imagesBoutiqueRepository, UploadImage $uploadImage, CallApi $callApi): Response
    {
        $boutique = $this->getUserBoutique();

        $form     = $this->createForm(BoutiqueType::class, $boutique);
        $form->get('search')->setData($boutique->getCity().' ('.$boutique->getPostcode().')');
        $form->get('city')->setData($boutique->getCity());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $boutique->setUser($this->getUser());
            $boutique->setModifiedAt(new \DateTimeImmutable());
            $boutique->setCity($form->get('city')->getData());
            $callApi->getBoutiqueAdressCoordinates($boutique, $form->get('city')->getData(),$form->get('citycode')->getData(),$form->get('adress')->getData());
            $boutiqueRepository->add($boutique, true);
            $boutiqueImage = $form->get('upload')->getData();
            if (count($boutiqueImage) <= 4 && count($boutiqueImage) >= 1 || empty($boutiqueImage)) {
                $imagesBoutiqueRepository->remove($imagesBoutiqueRepository->find(1),true);
                if (count($boutique->getImagesBoutiques()) > 4){
                    $imagesBoutiqueRepository->remove($imagesBoutiqueRepository->findFirstImagesbyBoutiqueId($boutique->getId())[0],true);
                }
                $uploadImage->uploadAndResizeImage($boutiqueImage, $boutique);
            }else{
                $this->addFlash('failure','4 photos max !');
                return $this->redirectToRoute('app_boutique_edit', ['id'=> $boutique->getId()], Response::HTTP_SEE_OTHER);
            }
            return $this->redirectToRoute('app_boutique_detail', ['id'=> $boutique->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/boutique/edit_boutique.html.twig', [
            'boutique' => $boutique,
            'form'     => $form,
        ]);
    }

    #[Route('/delete', name: 'app_boutique_delete', methods: ['POST'])]
    public function deleteBoutique($id,Request $request, BoutiqueRepository $boutiqueRepository, ImagesBoutiqueRepository $imagesBoutiqueRepository): Response
    {
        $boutique = $this->getUserBoutique();

        if ($this->isCsrfTokenValid('delete'.$boutique->getId(), $request->request->get('_token'))) {
            $imagesBoutique = $imagesBoutiqueRepository->findImagesbyBoutiqueId($id);
            foreach ($imagesBoutique as $image) {
                $imagesBoutiqueRepository->remove($image, true);
            }
            $boutiqueRepository->remove($boutique, true);
        }

        return $this->redirectToRoute('app_boutique_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/actif', name: 'app_boutique_actif', methods: ['POST', 'GET'])]
    public function toggleActif(BoutiqueRepository $boutiqueRepository): Response
    {
        $boutique = $this->getUserBoutique();

        if ($boutique->isActif()) {
            $boutique->setActif(false);
        }else{
            $boutique->setActif(true);
        }
        $boutiqueRepository->add($boutique,true);

        return $this->redirectToRoute('app_boutique_detail', ['id' => $boutique->getId()], Response::HTTP_SEE_OTHER);
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
            $image[] = $form->get('image')->getData();
            if(!empty($image[0])){
                $uploadImage->uploadAndResizeImage($image, $user);
            }
            $userRepository->add($user,true);
            return $this->redirectToRoute('boutique_view_profil', ['id'=> $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/boutique/profil/edit_profil.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    public function getUserBoutique()
    {
        $user      = $this->getUser();
        $boutiques = $user->getBoutiques();
        $boutique  = $boutiques[0];

        return $boutique;
    }
}
