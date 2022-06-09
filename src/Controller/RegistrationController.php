<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
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
                        // generate a signed url and email it to the user

            // do anything else you need here, like send an email
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()));
            $user->setRoles(['ROLE_USER']);
            $entityManager->persist($user);
            $entityManager->flush();

//                            $url = $this->generateUrl('app_verify_email', [], UrlGeneratorInterface::ABSOLUTE_URL);
//
//                 $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
//                (new TemplatedEmail())
//                    ->from(new Address('paul.joret@hotmail.fr', 'paulJoret'))
//                    ->to($form->get('email')->getData())
//                    ->subject('Please Confirm your Email')
//                    ->htmlTemplate('admin/emails/verification_email.html.twig')
//                    ->context([
//                        'username' => $user->getName(),
//                        'url'      => $url
//                    ])
//            );

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
//
//    #[Route('/register/success', name: 'app_register_success')]
//    public function sucessRegister(Request $request): Response
//    {
//        $user = $this->getUser();
//
//        return $this->render('front/registration/success_register.html.twig', [
//           'user' => $user
//        ]);
//    }

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
