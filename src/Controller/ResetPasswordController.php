<?php

namespace App\Controller;

use App\Form\ModifyPasswordType;
use App\Form\ResetPasswordFormType;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class ResetPasswordController extends AbstractController
{
    #[Route('/reset/password', name: 'app_reset_password')]
    public function resetPassword(Request $request, UserRepository $userRepository, TokenGeneratorInterface $tokenGenerator, MailerInterface $mailer): Response
    {
        $success = false;
        $form    = $this->createForm(ResetPasswordFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $email = $form->get('email')->getData();
            $user  = $userRepository->findOneBy(['email'=>$email]);
            if (empty($user)){
                $this->addFlash('failure','L\'email n\'existe pas !');
                return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
            }else{
                $token = $tokenGenerator->generateToken();
                $user->setToken($token);
                $userRepository->add($user,true);
                $url = $this->generateUrl('app_modif_password', ['token'=>urlencode($user->getToken()), 'id' => urlencode($user->getId())], UrlGeneratorInterface::ABSOLUTE_URL);

                $emailReset = (new TemplatedEmail())
                    ->from('reset_password@garden-connect.com')
                    ->to($email)
                    ->subject('Réinitialisation du mot de passe')
                    ->htmlTemplate('admin/emails/reset_password.html.twig')
                    ->context([
                        'username' => $user->getName(),
                        'url'      => $url
                    ]);
                    $mailer->send($emailReset);
                    $success = true;
            }
        }
        return $this->render('front/login/reset_password.html.twig', [
            'form'    => $form->createView(),
            'success' => $success
        ]);
    }

    #[Route('/modif/password/{id}/{token}', name: 'app_modif_password')]
    public function modifPassword($token,$id, Request $request, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = $userRepository->findOneBy(['id' => $id, 'token'=> $token]);
        if($user){
            $form = $this->createForm(ModifyPasswordType::class);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $newPassword = $userPasswordHasher->hashPassword( $user, $form->get('plainpassword')->getData());
                $user->setPassword($newPassword);
                $user->setToken(null);
                $userRepository->add($user,true);
                return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
            }
        }else{
            throw $this->createNotFoundException('error');
        }

        return $this->render('front/login/modif_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
