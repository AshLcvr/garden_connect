<?php

namespace App\Controller\Boutique;

use App\Entity\Boutique;
use App\Form\BoutiqueType;
use App\Service\UploadImage;
use App\Entity\ImagesBoutique;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManager;
use App\Repository\UserRepository;
use App\Repository\AnnonceRepository;
use App\Repository\BoutiqueRepository;
use App\Repository\ImagesBoutiqueRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authenticator\FormLoginAuthenticator;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

//#[Route('/boutique')]
class BoutiqueController extends AbstractController
{
    #[Route('/boutique/', name: 'app_boutique_index', methods: ['GET'])]
    public function indexBoutique(BoutiqueRepository $boutiqueRepository): Response
    {
        $user = $this->getUser();
        $boutiques = $user->getBoutiques();
        $boutique = $boutiques[0];

        return $this->render('front/boutique/index_boutique.html.twig', [
            'boutiques' => $boutiques,
            'boutique' => $boutique,
        ]);
    }

    #[Route('/nouvelleboutique', name: 'app_boutique_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BoutiqueRepository $boutiqueRepository, UploadImage $uploadImage, ImagesBoutiqueRepository $imagesBoutiqueRepository, UserRepository $userRepository, FormLoginAuthenticator $formLoginAuthenticator, UserAuthenticatorInterface $userAuthenticator): Response
    {
        $user = $this->getUser();
        $boutique = new Boutique();
        $form = $this->createForm(BoutiqueType::class, $boutique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formatedTel =  $form->get('indicatif')->getData() . $form->get('telephone')->getData();
            $boutique->setTelephone($formatedTel);
            $boutique->setCreatedAt(new \DateTimeImmutable());
            $boutique->setActif(true);
            $boutique->setUser($user);
            $boutiqueRepository->add($boutique, true);
            $boutiqueImage = $form->get('upload')->getData();
            if (count($boutiqueImage) <= 4 || empty($boutiqueImage)) {
                if (empty($boutiqueImage)) {
                    $imageDefault = new ImagesBoutique();
                    $imageDefault->setTitle('imageBoutiqueDefault.jpg');
                    $imageDefault->setBoutique($boutique);
                    $imagesBoutiqueRepository->add($imageDefault, true);
                } else {
                    $uploadImage->uploadBoutique($boutiqueImage, $boutique->getId());
                }
                $user->setRoles(['ROLE_VENDEUR']);
                $userRepository->add($user, true);
                $userAuthenticator->authenticateUser($user, $formLoginAuthenticator, $request);
                return $this->redirectToRoute('app_boutique_index', [], Response::HTTP_SEE_OTHER);
            }else{
                $this->addFlash('failure','4 photos max !');
                return $this->redirectToRoute('app_boutique_new', [], Response::HTTP_SEE_OTHER);
            }

        }

        return $this->renderForm('front/boutique/new_boutique.html.twig', [
            'boutique' => $boutique,
            'form' => $form,
        ]);
    }

    #[Route('/boutique/detail', name: 'app_boutique_detail', methods: ['GET', 'POST'])]
    public function show(): Response
    {
        $user = $this->getUser();
        $boutiques = $user->getBoutiques();
        $boutique = $boutiques[0];

        return $this->render('front/boutique/detail_boutique.html.twig', [
            'boutique' => $boutique,
        ]);
    }

    #[Route('/boutique/{id}/edit', name: 'app_boutique_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Boutique $boutique, BoutiqueRepository $boutiqueRepository, UploadImage $uploadImage): Response
    {
        $form = $this->createForm(BoutiqueType::class, $boutique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $boutique->setUser($this->getUser());
            $boutiqueRepository->add($boutique, true);
            $boutiqueImage = $form->get('upload')->getData();
            if (count($boutiqueImage) <= 4 || empty($boutiqueImage)) {
                $uploadImage->uploadBoutique($boutiqueImage, $boutique->getId());
            }else{
                $this->addFlash('failure','4 photos max !');
                return $this->redirectToRoute('app_boutique_edit', ['id'=> $boutique->getId()], Response::HTTP_SEE_OTHER);
            }
            return $this->redirectToRoute('app_boutique_detail', ['id'=> $boutique->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/boutique/edit_boutique.html.twig', [
            'boutique' => $boutique,
            'form' => $form,
        ]);
    }

    #[Route('/boutique/{id}', name: 'app_boutique_delete', methods: ['POST'])]
    public function delete(Request $request, Boutique $boutique, BoutiqueRepository $boutiqueRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$boutique->getId(), $request->request->get('_token'))) {
            $boutiqueRepository->remove($boutique, true);
        }

        return $this->redirectToRoute('app_boutique_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/boutique/{id}', name: 'app_boutique_inactif', methods: ['GET','POST'])]
    public function setInactif(Request $request, Boutique $boutique, BoutiqueRepository $boutiqueRepository): Response
    {
        $boutique->setActif(false);
        $boutique->setModifiedAt(new \DateTimeImmutable());
        $boutiqueRepository->add($boutique, true);

        return $this->redirectToRoute('app_boutique_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/actif', name: 'app_boutique_actif', methods: ['POST', 'GET'])]
    public function toggleActif(Request $request, Boutique $boutique, BoutiqueRepository $boutiqueRepository): Response
    {
        if ($boutique->isActif()) {
            $boutique->setActif(false);
        }else{
            $boutique->setActif(true);
        }
        $boutiqueRepository->add($boutique,true);

        return $this->redirectToRoute('app_boutique_detail', ['id' => $boutique->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/viewboutique/{id}', name: 'view_boutique', methods: ['GET'])]
    public function oneBoutique(Boutique $boutique, ImagesBoutiqueRepository $imagesBoutiqueRepository)
    {
        $boutique_user = $boutique->getUser();
        $boutique_city = $boutique->getCity();
        $boutique_telephone = $boutique->getTelephone();
        $boutique_title = $boutique->getTitle();
        $boutique_createdAt = $boutique->getCreatedAt();
        $boutique_description = $boutique->getDescription();
        $boutique_annonces = $boutique->getAnnonces();
        $imagesBoutiques = $imagesBoutiqueRepository->findAll();

        return $this->render(
            'front/boutique/viewboutique.html.twig',
            [
                'user' => $boutique_user,
                'city' => $boutique_city,
                'telephone' => $boutique_telephone,
                'title' => $boutique_title,
                'description' => $boutique_description,
                'createdAt' => $boutique_createdAt,
                'annonces' => $boutique_annonces,
                'images' => $imagesBoutiques
            ]
        );
    }
    #[Route('/viewboutique/{id}/{id_annonce}', name: 'view_boutique_annonce_focus', methods: ['GET'])]
    public function oneBoutiqueFocusAnnonce(AnnonceRepository $annonceRepository, $id, $id_annonce)
    {
        $annonce = $annonceRepository->findBy([
            'boutique' => $id,
            'id' => $id_annonce
        ]);
        // dd($annonce);

        return $this->render(
            'front/boutique/viewboutique.html.twig',
            [
                // 'annonces' => $annonces
            ]
        );
    }
}
