<?php

namespace App\Controller;

use App\Entity\Boutique;
use App\Entity\ImagesBoutique;
use App\Entity\User;
use App\Form\BoutiqueType;
use App\Form\RegistrationFormType;
use App\Repository\BoutiqueRepository;
use App\Repository\ImagesBoutiqueRepository;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Service\CallApi;
use App\Service\UploadImage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\FormLoginAuthenticator;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;
    private FormLoginAuthenticator $authenticator;

    public function __construct(EmailVerifier $emailVerifier, FormLoginAuthenticator $authenticator)
    {
        $this->emailVerifier = $emailVerifier;
        $this->authenticator = $authenticator;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, UserAuthenticatorInterface $authenticatorManager): RedirectResponse|Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()));
            $user->setRoles(['ROLE_USER']);
            $user->setActif(true);
            $user->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($user);
            $entityManager->flush();

            $authenticatorManager->authenticateUser($user, $this->authenticator, $request);
            if($form->get('role')->getData() === 'vendeur' ){
                $this->addFlash('register_vendeur', 'Bienvenue sur Garden Connect ! Votre inscription a bien été prise en compte. Un email de validation vous a été envoyé. Vous pouvez maintenant créer votre boutique !');
                 return $this->redirectToRoute('app_boutique_new');
            }else{
                $this->addFlash('register_user', 'Bienvenue sur Garden Connect ! Votre inscription a bien été prise en compte. Un email de validation vous a été envoyé.');
                return $this->redirectToRoute('homepage');
            }
        }
        return $this->render('front/registration/register.html.twig', [
            'registrationForm' => $form->createView()
        ]);
    }

    #[Route('/nouvelleboutique', name: 'app_boutique_new', methods: ['GET', 'POST'])]
    public function newBoutique(Request $request, BoutiqueRepository $boutiqueRepository, UploadImage $uploadImage, ImagesBoutiqueRepository $imagesBoutiqueRepository, UserRepository $userRepository, FormLoginAuthenticator $formLoginAuthenticator, UserAuthenticatorInterface $userAuthenticator, CallApi $callApi): Response
    {
        $user = $this->getUser();
        $boutique = new Boutique();
        $form = $this->createForm(BoutiqueType::class, $boutique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $boutique->setUser($user);
            $boutique->setCity($form->get('city')->getData());
            $callApi->getBoutiqueAdressCoordinates($boutique,$form->get('city')->getData(),$form->get('citycode')->getData(),$form->get('adress')->getData());
            $boutique->setCardActive(true);
            $boutique->setActif(true);
            $boutique->setCreatedAt(new \DateTimeImmutable());
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
            } else {
                $this->addFlash('failure', '4 photos max !');
                return $this->redirectToRoute('app_boutique_new', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->renderForm('front/boutique/new_boutique.html.twig', [
            'boutique' => $boutique,
            'form' => $form,
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }
}
