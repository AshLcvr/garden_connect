<?php 

namespace App\Controller\Profil;

use App\Entity\User;
use App\Entity\Message;
use App\Form\MessageType;
use App\Entity\Conversation;
use App\Form\ConversationType;
use App\Repository\MessageRepository;
use App\Repository\ConversationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/profil')]
class MessagerieUserController extends AbstractController
{
    #[Route('/user/messagerie', name: 'user_messagerie')]
    public function messagerieUser(Request $request, PaginatorInterface $paginator)
    {
        $user = $this->getUser();
        $conversationsCorresp = $user->getConversationsCorresp();
        $conversationsInit = $user->getConversationsInit();
        $conversations = [];

        
        $nbrNonlus = 0;
        $tabNbrNonlus = [];

        foreach ($conversationsCorresp as $key => $conv) {
            $conversations[] = $conv;
            if ($conv->isIsRead() === false) {
                $nbrNonlus = $nbrNonlus + 1;
            }
            foreach ($conv->getMessages() as $key => $mess) {
                if ($mess->isIsRead() === false && $mess->getExpediteur()->getId() != $this->getUser()->getId()) {
                    $nbrNonlus += 1;
                }
            }
            $tabNbrNonlus[$conv->getId()] = $nbrNonlus;
            $nbrNonlus = 0;
        }
        foreach ($conversationsInit as $key => $conv) {
            $conversations[] = $conv;
            foreach ($conv->getMessages() as $key => $mess) {
                if ($mess->isIsRead() == false && $mess->getExpediteur()->getId() != $this->getUser()->getId()) {
                    $nbrNonlus += 1;
                }
            }
            $tabNbrNonlus[$conv->getId()] = $nbrNonlus;
            $nbrNonlus = 0;
        }
        usort($conversations, function(Conversation $a, Conversation $b){
            return $a->getModifiedAt()>$b->getModifiedAt()?-1:1;
        });
        $conversations = $this->maPagination($conversations, $paginator, $request, 5);

        return $this->renderForm('front/profil/messagerie/messagerie.html.twig', [
            'conversations' => $conversations,
            'tabNbrNonlus' => $tabNbrNonlus
        ]);
    }

    #[Route('/new-conversation-user/{id}', name: 'new_conversation_user')]
    public function newConversation(Request $request, ConversationRepository $conversationRepository, User $user): Response
    {
        $conversation = new Conversation();
        $form = $this->createForm(ConversationType::class, $conversation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $conversation->setCreatedAt(new \DateTimeImmutable());
            $conversation->setModifiedAt(new \DateTimeImmutable());
            $conversation->setIsRead(false);
            $conversation->setUser($this->getUser());
            $conversation->setCorrespondant($user);
            $conversationRepository->add($conversation, true);

            return $this->redirectToRoute('user_messagerie', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/profil/messagerie/new_conversation.html.twig', [
            'conversation' => $conversation,
            'form' => $form,
        ]);
    }

    #[Route('/user/messagerie/{id}', name: 'user_messagerie_message')]
    public function newMessage(Request $request, MessageRepository $messageRepository, Conversation $conversation, ConversationRepository $conversationRepository): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($conversation->getCorrespondant()->getId() === $this->getUser()->getId()) {
            if ($conversation->isIsRead() === false) {
                $conversation->setIsRead(true);
            }
            foreach ($conversation->getMessages() as $key => $mess) {
                if ($mess->getExpediteur()->getId() != $this->getUser()->getId()) {
                    $mess->setIsRead(true);
                }
            }
        }
        $conversationRepository->add($conversation, true);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setConversation($conversation);
            $message->setIsRead(false);
            $message->setExpediteur($this->getUser());
            $message->setCreatedAt(new \DateTimeImmutable());
            $messageRepository->add($message, true);

            $conversation->setModifiedAt(new \DateTimeImmutable());
            $conversationRepository->add($conversation, true);

            return $this->redirectToRoute('user_messagerie_message', ['id' => $conversation->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/profil/messagerie/message.html.twig', [
            'message' => $message,
            'form' => $form,
            'conversation' => $conversation
        ]);
    }
}