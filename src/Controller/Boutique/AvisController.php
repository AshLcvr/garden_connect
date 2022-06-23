<?php

namespace App\Controller\Boutique;

use App\Entity\Avis;
use App\Form\AvisFormType;
use App\Form\AvisType;
use App\Repository\AvisRepository;
use App\Repository\BoutiqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/avis')]
class AvisController extends AbstractController
{
    #[Route('/avis-recus', name: 'app_avis_received', methods: ['GET'])]
    public function receivedAvis( AvisRepository $avisRepository, BoutiqueRepository $boutiqueRepository): Response
    {
        $user = $this->getUser();
        $boutique = $boutiqueRepository->findOneBy(['user' => $user->getId()]);
        $receivedAvis = $avisRepository->findBy(['boutique' => $boutique]);

        return $this->render('front/boutique/avis/avis_recus.html.twig', [
            'avis' => $receivedAvis
        ]);
    }

    #[Route('/avis-emis', name: 'app_avis_sent', methods: ['GET', 'POST'])]
    public function sentAvis(AvisRepository $avisRepository): Response
    {
        $user = $this->getUser();
        $sentAvis = $avisRepository->findBy(['user' => $user]);

        return $this->render('front/boutique/avis/avis_emis.html.twig', [
            'avis' => $sentAvis
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
