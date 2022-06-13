<?php

namespace App\Controller\Admin;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\User;
use App\Form\ConversationType;
use App\Form\MessageType;
use App\Repository\ConversationRepository;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConversationController extends AbstractController
{
    #[Route('/conversation', name: 'app_conversation_index')]
    public function index(UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        $conversationsCorresp = $user->getConversationsCorresp();
        $conversationsInit = $user->getConversationsInit();

        return $this->renderForm('admin/conversation/index.html.twig', [
            'conversationsCorresp' => $conversationsCorresp,
            'conversationsInit' => $conversationsInit
        ]);
    }

    #[Route('/conversation/{id}', name: 'new_conversation')]
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
