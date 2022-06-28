<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\User;
use App\Entity\Favory;
use App\Form\AvisFormType;
use App\Form\EditProfilType;
use App\Service\UploadImage;
use App\Repository\AvisRepository;
use App\Repository\UserRepository;
use App\Repository\FavoryRepository;
use App\Repository\MessageRepository;
use App\Repository\ConversationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilController extends AbstractController
{
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
    public function avis_profil(Request $request, PaginatorInterface $paginator)
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
        $mesAvis = $paginator->paginate(
            $mesAvis, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            5 // Nombre de résultats par page
        );
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

    #[Route('/profil/avis-delete/{id}', name: 'profil_avis_delete', methods: ['POST'])]
    public function delete_avis(Request $request, Avis $avi, AvisRepository $avisRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$avi->getId(), $request->request->get('_token'))) {
            $avisRepository->remove($avi, true);
        }

        return $this->redirectToRoute('profil_avis', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/profil/favory', name: 'profil_favory')]
    public function profil_favory(Request $request, PaginatorInterface $paginator): Response
    {
        $favories = $this->getUser()->getFavories();

        
        $globalRating = [];
        $totalGlobalRating = [];
        $numberAvis = [];
        $totalNumberAvis = [];

        if ($favories){
            foreach ($favories as $key => $fav) {
                $numberAvis = count($fav->getBoutique()->getAvis());
                $totalNumberAvis[$fav->getBoutique()->getId()] = count($fav->getBoutique()->getAvis());
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
        $favories = $paginator->paginate(
            $favories, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            5 // Nombre de résultats par page
        );

        return $this->renderForm('front/profil/favories/favories.html.twig', [
            'favories' => $favories,
            'totalGlobalRating' => $totalGlobalRating,
            'totalNumberAvis' => $totalNumberAvis
        ]);
    }
    #[Route('/profil/favory/{id}', name: 'profil_favory_delete', methods: ['POST'])]
    public function delete_favory(Request $request, Favory $favory, FavoryRepository $favoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$favory->getId(), $request->request->get('_token'))) {
            $favoryRepository->remove($favory, true);
        }

        return $this->redirectToRoute('profil_favory', [], Response::HTTP_SEE_OTHER);
    }
}