<?php

namespace App\Controller\Profil;

use App\Controller\DefaultController;
use App\Entity\Avis;
use App\Entity\User;
use App\Entity\Favory;
use App\Form\AvisFormType;
use App\Form\EditProfilType;
use App\Service\UploadImage;
use App\Repository\AvisRepository;
use App\Repository\UserRepository;
use App\Repository\FavoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use function Symfony\Config\em;

#[Route('/profil')]
class ProfilController extends AbstractController
{
    #[Route('/', name: 'profil')]
    public function profil(TokenGeneratorInterface $tokenGenerator, UserRepository $userRepository)
    {
        $user = $this->getUser();
        $token = $tokenGenerator->generateToken();
        $user->setToken($token);
        $userRepository->add($user, true);
        
        return $this->render('front/profil/profil.html.twig', [
            'user' => $user
        ]);
    }
    #[Route('/edit', name: 'edit_profil', methods: ['GET', 'POST'])]
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
            return $this->redirectToRoute('profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/profil/edit_profil.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/avis', name: 'profil_avis')]
    public function avis_profil(Request $request, PaginatorInterface $paginator,DefaultController $defaultController)
    {
        $user = $this->getUser();
        $avis = $user->getAvis();

        $mesAvis = [];
        $globalRating = [];
        $totalGlobalRating = [];
        $numberAvis = [];
        $totalNumberAvis = [];
        $total = [];

        if ($avis){
            foreach ($avis as $key => $avi) {
                $mesAvis[] = $avi;
                $numberAvis = count($avi->getBoutique()->getAvis());
                $totalNumberAvis[$avi->getBoutique()->getId()] = $numberAvis;
                foreach ($avi->getBoutique()->getAvis() as $key => $value) {
                    $total[] = $value->getRating();
                }
                if ($numberAvis) {
                    $globalRating[$avi->getBoutique()->getId()] = array_sum($total)/$numberAvis;
                    $total = [];
                    $totalGlobalRating[$avi->getBoutique()->getId()] = $globalRating[$avi->getBoutique()->getId()];
                    $globalRating = [];
                }
            }
        }else{
            $totalGlobalRating = 0;
        }
        usort($mesAvis, function(Avis $a, Avis $b){
            return $a->getCreatedAt()>$b->getCreatedAt()?-1:1;
        });
        $mesAvis = $defaultController::maPagination($mesAvis, $paginator, $request, 5);

        return $this->render('front/profil/avis/avis.html.twig', [
            'user' => $user,
            'mesAvis' => $mesAvis,
            'totalGlobalRating' => $totalGlobalRating,
            'totalNumberAvis' => $totalNumberAvis
        ]);
    }
    #[Route('/avis/{id}', name: 'profil_avis_edit')]
    public function edit_avis(Request $request, Avis $avis, AvisRepository $avisRepository)
    {
        $form = $this->createForm(AvisFormType::class, $avis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rating = $form->get('rating')->getData();
            $avis->setRating($rating);
            $avisRepository->add($avis, true);

            return $this->redirectToRoute('profil_avis', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/profil/avis/edit_avis.html.twig', [
            'avi' => $avis,
            'form' => $form,
        ]);
    }

    #[Route('/avis-delete/{id}', name: 'profil_avis_delete', methods: ['POST'])]
    public function delete_avis(Request $request, Avis $avi, AvisRepository $avisRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$avi->getId(), $request->request->get('_token'))) {
            $avisRepository->remove($avi, true);
        }

        return $this->redirectToRoute('profil_avis', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/favory', name: 'profil_favory')]
    public function profil_favory(Request $request, PaginatorInterface $paginator,DefaultController $defaultController): Response
    {
        $favories = $this->getUser()->getFavories();

        $globalRating = [];
        $totalGlobalRating = [];
        $totalNumberAvis = [];

        if ($favories){
            foreach ($favories as $key => $fav) {
                $numberAvis = count($fav->getBoutique()->getAvis());
                $totalNumberAvis[$fav->getBoutique()->getId()] = $numberAvis;
                foreach ($fav->getBoutique()->getAvis() as $key => $value) {
                    $globalRating[] = $value->getRating();
                }
                if ($numberAvis) {
                    $totalGlobalRating[$fav->getBoutique()->getId()] = round(array_sum($globalRating)/$numberAvis);
                    $globalRating = [];
                }
            }
        }else{
            $totalGlobalRating = 0;
        }
        $favories = $defaultController::maPagination($favories, $paginator, $request, 5);

        return $this->renderForm('front/profil/favories/favories.html.twig', [
            'favories' => $favories,
            'totalGlobalRating' => $totalGlobalRating,
            'totalNumberAvis' => $totalNumberAvis
        ]);
    }

    #[Route('/favory/{id}', name: 'profil_favory_delete', methods: ['POST'])]
    public function delete_favory(Request $request, Favory $favory, FavoryRepository $favoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$favory->getId(), $request->request->get('_token'))) {
            $favoryRepository->remove($favory, true);
        }

        return $this->redirectToRoute('profil_favory', [], Response::HTTP_SEE_OTHER);
    }
}