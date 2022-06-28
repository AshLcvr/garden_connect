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

#[Route('/avis')]
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
        $mesAvis = $paginator->paginate(
            $mesAvis, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            5 // Nombre de résultats par page
        );

        return $this->render('front/boutique/avis/avis_recus.html.twig', [
            'mesAvis' => $mesAvis,
            'totalGlobalRating' => $totalGlobalRating,
            'totalNumberAvis' => $totalNumberAvis
        ]);
    }

    #[Route('/avis-emis', name: 'app_avis_sent', methods: ['GET', 'POST'])]
    public function sentAvis(Request $request, AvisRepository $avisRepository, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();
        $sentAvis = $avisRepository->findBy(['user' => $user]);

        $globalRating = [];
        $totalGlobalRating = [];
        $numberAvis = [];
        $totalNumberAvis = [];
        $mesAvis = [];

        if ($sentAvis){
            foreach ($sentAvis as $key => $avi) {
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

        return $this->render('front/boutique/avis/avis_emis.html.twig', [
            'mesAvis' => $mesAvis,
            'totalGlobalRating' => $totalGlobalRating,
            'totalNumberAvis' => $totalNumberAvis
        ]);
    }

    #[Route('/edit/{id}', name: 'app_avis_edit', methods: ['GET', 'POST'])]
    public function editAvis(Request $request, Avis $avis, AvisRepository $avisRepository): Response
    {
        $form = $this->createForm(AvisFormType::class, $avis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avisRepository->add($avis, true);

            return $this->redirectToRoute('app_avis_index', [], Response::HTTP_SEE_OTHER);
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
