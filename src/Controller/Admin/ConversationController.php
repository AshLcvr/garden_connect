<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Message;
use App\Form\MessageType;
use App\Entity\Conversation;
use App\Form\ConversationType;
use App\Repository\AnnonceRepository;
use App\Repository\BoutiqueRepository;
use App\Repository\UserRepository;
use App\Repository\MessageRepository;
use App\Repository\ConversationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
class ConversationController extends AbstractController
{
    #[Route('/conversation-admin/{id}', name: 'app_conversation_index')]
    public function index(User $user): Response
    {
        $nbrNonlus = [];
        $conversationsCorresp = $user->getConversationsCorresp();
        foreach ($conversationsCorresp as $key => $conv) {
            foreach ($conv->getMessages() as $key => $mess) {
                if ($mess->isIsRead()) {
                    $nbrNonlus[$conv->getId()] .= 1;
                }
            }
        }

        
        $conversationsInit = $user->getConversationsInit();

        return $this->renderForm('admin/conversation/index.html.twig', [
            'conversationsCorresp' => $conversationsCorresp,
            'conversationsInit' => $conversationsInit
        ]);
    }

    #[Route('/new-conversation/{id}', name: 'new_conversation')]
    public function newConversation(Request $request, ConversationRepository $conversationRepository, User $user): Response
    {
        $conversation = new Conversation();
        $form = $this->createForm(ConversationType::class, $conversation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $conversation->setCreatedAt(new \DateTimeImmutable());
            $conversation->setUser($this->getUser());
            $conversation->setCorrespondant($user);
            $conversationRepository->add($conversation, true);

            return $this->redirectToRoute('conversation_message', ['id' => $conversation->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/conversation/new.html.twig', [
            'conversation' => $conversation,
            'form' => $form,
        ]);
    }
    #[Route('/conversation/annonce/{id}/{id_annonce}', name: 'new_conversation_with_id_annonce', requirements: ['id' => '\d+', 'id_annonce' => '\d+'])]
    public function newConversationWithIdAnnonce(Request $request, ConversationRepository $conversationRepository, User $user, $id_annonce, AnnonceRepository $annonceRepository): Response
    {
        $annonce = $annonceRepository->find($id_annonce);
        $annonceUrl = $this->generateUrl('app_annonce_edit', ['id' => urlencode($annonce->getId())], UrlGeneratorInterface::ABSOLUTE_URL);
        $conversation = new Conversation();
        $conversation->setPremierMessage($annonceUrl);
        $form = $this->createForm(ConversationType::class, $conversation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $conversation->setCreatedAt(new \DateTimeImmutable());
            $conversation->setUser($this->getUser());
            $conversation->setCorrespondant($user);
            $conversationRepository->add($conversation, true);

            return $this->redirectToRoute('app_conversation_index', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/conversation/new.html.twig', [
            'conversation' => $conversation,
            'form' => $form,
        ]);
    }

    #[Route('/conversation/boutique/{id}/{id_boutique}', name: 'new_conversation_with_id_boutique', requirements: ['id' => '\d+', 'id_boutique' => '\d+'])]
    public function newConversationWithIdBoutique(Request $request, ConversationRepository $conversationRepository, User $user, $id_boutique, BoutiqueRepository $boutiqueRepository): Response
    {
        $boutique = $boutiqueRepository->find($id_boutique);
        $boutiqueUrl = $this->generateUrl('app_boutique_edit', ['id' => urlencode($boutique->getId())], UrlGeneratorInterface::ABSOLUTE_URL);
        $conversation = new Conversation();
        $conversation->setPremierMessage($boutiqueUrl);
        $form = $this->createForm(ConversationType::class, $conversation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $conversation->setCreatedAt(new \DateTimeImmutable());
            $conversation->setUser($this->getUser());
            $conversation->setCorrespondant($user);
            $conversationRepository->add($conversation, true);

            return $this->redirectToRoute('app_conversation_index', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/conversation/new.html.twig', [
            'conversation' => $conversation,
            'form' => $form,
        ]);
    }

    #[Route('/conversation/message/{id}', name: 'conversation_message')]
    public function newMessage(Request $request, MessageRepository $messageRepository, Conversation $conversation): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setConversation($conversation);
            $message->setExpediteur($this->getUser());
            $messageRepository->add($message, true);

            return $this->redirectToRoute('conversation_message', ['id' => $conversation->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/conversation/message/index.html.twig', [
            'message' => $message,
            'form' => $form,
            'conversation' => $conversation
        ]);
    }
}
