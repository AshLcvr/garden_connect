<?php

namespace App\Controller\Boutique;

use App\Entity\Boutique;
use App\Form\BoutiqueType;
use App\Repository\BoutiqueRepository;
use App\Repository\UserRepository;
use App\Service\UploadImage;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Psr\Log\alert;

#[Route('/boutique')]
class BoutiqueController extends AbstractController
{
    #[Route('/', name: 'app_boutique_index', methods: ['GET'])]
    public function indexBoutique(BoutiqueRepository $boutiqueRepository): Response
    {
        $user = $this->getUser();
        $boutiques = $user->getBoutiques();
        $nbBoutiques = count($user->getBoutiques());
        $boutique = $boutiques[0];

        return $this->render('front/boutique/index_boutique.html.twig', [
            'boutiques' => $boutiques,
            'nbBoutiques' => $nbBoutiques,
            'boutique' => $boutique,
        ]);
    }

    #[Route('/new', name: 'app_boutique_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BoutiqueRepository $boutiqueRepository, UploadImage $uploadImage): Response
    {
        $boutique = new Boutique();
        $form = $this->createForm(BoutiqueType::class, $boutique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $boutique->setCreatedAt(new \DateTimeImmutable());
            $boutique->setActif(true);
            $boutique->setUser($this->getUser());
            $boutiqueRepository->add($boutique, true);
            $boutiqueImage = $form->get('upload')->getData();
            if (count($boutiqueImage) <= 4 || empty($boutiqueImage)) {
                $uploadImage->upload($boutiqueImage, $boutique->getId());
            }else{
                $this->addFlash('failure','4 photos max !');
                return $this->redirectToRoute('app_boutique_new', [], Response::HTTP_SEE_OTHER);
            }
            return $this->redirectToRoute('app_boutique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/boutique/new_boutique.html.twig', [
            'boutique' => $boutique,
            'form' => $form,
        ]);
    }

    #[Route('/detail', name: 'app_boutique_detail', methods: ['GET', 'POST'])]
    public function show(): Response
    {
        $user = $this->getUser();
        $boutiques = $user->getBoutiques();
        $boutique = $boutiques[0];

//        $images = $boutique->getImagesBoutiques();

        return $this->render('front/boutique/detail_boutique.html.twig', [
            'boutique' => $boutique,
//            'images' => $images
        ]);
    }

    #[Route('/{id}/edit', name: 'app_boutique_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Boutique $boutique, BoutiqueRepository $boutiqueRepository, UploadImage $uploadImage): Response
    {
        $form = $this->createForm(BoutiqueType::class, $boutique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $boutique->setUser($this->getUser());
            $boutiqueRepository->add($boutique, true);
            $boutiqueImage = $form->get('upload')->getData();
            if (count($boutiqueImage) <= 4 || empty($boutiqueImage)) {
                $uploadImage->upload($boutiqueImage, $boutique->getId());
            }else{
                $this->addFlash('failure','4 photos max !');
                return $this->redirectToRoute('app_boutique_new', [], Response::HTTP_SEE_OTHER);
            }
            return $this->redirectToRoute('app_boutique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/boutique/edit_boutique.html.twig', [
            'boutique' => $boutique,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_boutique_delete', methods: ['POST'])]
    public function delete(Request $request, Boutique $boutique, BoutiqueRepository $boutiqueRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$boutique->getId(), $request->request->get('_token'))) {
            $boutiqueRepository->remove($boutique, true);
        }

        return $this->redirectToRoute('app_boutique_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_boutique_inactif', methods: ['GET','POST'])]
    public function setInactif(Request $request, Boutique $boutique, BoutiqueRepository $boutiqueRepository): Response
    {
        $boutique->setActif(false);
        $boutique->setModifiedAt(new \DateTimeImmutable());
        $boutiqueRepository->add($boutique, true);

        return $this->redirectToRoute('app_boutique_index', [], Response::HTTP_SEE_OTHER);
    }
}
