<?php

namespace App\Controller\Boutique;

use App\Entity\Avis;
use App\Form\AvisType;
use App\Form\AvisFormType;
use App\Repository\AvisRepository;
use App\Repository\BoutiqueRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/boutique/avis')]
class AvisController extends AbstractController
{
    #[Route('/avis-recus', name: 'app_avis_received', methods: ['GET'])]
    public function receivedAvis(Request $request, AvisRepository $avisRepository, BoutiqueRepository $boutiqueRepository, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();
        $boutique = $boutiqueRepository->findOneBy(['user' => $user->getId()]);
        $receivedAvis = $avisRepository->findBy(['boutique' => $boutique]);

        $globalRating = [];
        $totalGlobalRating = [];
        $numberAvis = [];
        $totalNumberAvis = [];
        $mesAvis = [];

        if ($receivedAvis){
            foreach ($receivedAvis as $key => $avi) {
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
        
        $mesAvis = $this->maPagination($mesAvis, $paginator, $request, 6);

        return $this->render('front/boutique/avis/avis_recus.html.twig', [
            'mesAvis'           => $mesAvis,
            'totalGlobalRating' => $totalGlobalRating,
            'totalNumberAvis'   => $totalNumberAvis
        ]);
    }

    #[Route('/avis-emis', name: 'app_avis_sent', methods: ['GET', 'POST'])]
    public function sentAvis(Request $request, AvisRepository $avisRepository, PaginatorInterface $paginator): Response
    {
        $user     = $this->getUser();
        $sentAvis = $avisRepository->findBy(['user' => $user]);

        $mesAvis           = [];
        $globalRating = [];
        $totalGlobalRating = [];
        $numberAvis = [];
        $totalNumberAvis = [];
        $total = [];

        if ($sentAvis){
            foreach ($sentAvis as $key => $avi) {
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
        $mesAvis = $this->maPagination($mesAvis, $paginator, $request, 5);

        return $this->render('front/boutique/avis/avis_emis.html.twig', [
            'mesAvis' => $mesAvis,
            'totalGlobalRating' => $totalGlobalRating,
            'totalNumberAvis' => $totalNumberAvis
        ]);
    }

    #[Route('/edit/{id}', name: 'app_avis_edit', methods: ['GET', 'POST'])]
    public function editAvis( Avis $avis,Request $request, AvisRepository $avisRepository): Response
    {
        $user = $this->getUser();
        if ($avis->getUser() !== $user) {
            return $this->redirectToRoute('403', [], Response::HTTP_SEE_OTHER);
        }

        $form = $this->createForm(AvisFormType::class, $avis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rating = $form->get('rating')->getData();
            $avis->setRating($rating);
            $avisRepository->add($avis, true);

            return $this->redirectToRoute('app_avis_sent', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/boutique/avis/edit_avis.html.twig', [
            'avi' => $avis,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_avis_delete', methods: ['POST'])]
    public function delete(Request $request, Avis $avi, AvisRepository $avisRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$avi->getId(), $request->request->get('_token'))) {
            $avisRepository->remove($avi, true);
        }

        return $this->redirectToRoute('app_avis_sent', [], Response::HTTP_SEE_OTHER);
    }
}
