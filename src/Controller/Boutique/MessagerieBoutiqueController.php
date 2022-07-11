<?php 

namespace App\Controller\Boutique;

use App\Entity\User;
use App\Entity\Message;
use App\Form\MessageType;
use App\Entity\Conversation;
use App\Form\ConversationType;
use App\Repository\UserRepository;
use App\Repository\MessageRepository;
use App\Repository\ConversationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/boutique')]
class MessagerieBoutiqueController extends AbstractController
{

    #[Route('/messagerie', name: 'boutique_messagerie')]
    public function boutiqueMessagerie(Request $request, PaginatorInterface $paginator)
    {
        $user = $this->getUser();
        $conversationsCorresp = $user->getConversationsCorresp();
        $conversationsInit = $user->getConversationsInit();

        $conversations = [];
        $nbrNonlus = 0;
        $tblNbrNonlus = [];

        foreach ($conversationsCorresp as $key => $value) {
            $conversations[] = $value;
            if ($value->isIsRead() === false) {
                $nbrNonlus = $nbrNonlus + 1;
            }
            foreach ($value->getMessages() as $key => $mess) {
                if ($mess->isIsRead() === false && $mess->getExpediteur()->getId() != $this->getUser()->getId()) {
                    $nbrNonlus += 1;
                }
            }
            $tblNbrNonlus[$value->getId()] = $nbrNonlus;
            $nbrNonlus = 0;
        }
        foreach ($conversationsInit as $key => $value) {
            $conversations[] = $value;
            foreach ($value->getMessages() as $key => $mess) {
                if ($mess->isIsRead() == false && $mess->getExpediteur()->getId() != $this->getUser()->getId()) {
                    $nbrNonlus += 1;
                }
            }
            $tblNbrNonlus[$value->getId()] = $nbrNonlus;
            $nbrNonlus = 0;
        }
        $conversations = $this->maPagination($conversations, $paginator, $request, 5);

        return $this->renderForm('front/boutique/messagerie/index.html.twig', [
            'conversations' => $conversations,
            'tblNbrNonlus' => $tblNbrNonlus
        ]);
    }

    #[Route('/messagerie/new-conversation/{id}', name: 'boutique_messagerie_new_conversation')]
    public function newConversation(Request $request, ConversationRepository $conversationRepository, User $user): Response
    {
        $conversation = new Conversation();
        $form = $this->createForm(ConversationType::class, $conversation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $conversation->setCreatedAt(new \DateTimeImmutable());
            $conversation->setIsRead(false);
            $conversation->setUser($this->getUser());
            $conversation->setCorrespondant($user);
            $conversationRepository->add($conversation, true);

            return $this->redirectToRoute('boutique_messagerie', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/boutique/messagerie/new.html.twig', [
            'conversation' => $conversation,
            'form' => $form,
        ]);
    }

    #[Route('/messagerie/message/{id}', name: 'boutique_messagerie_message')]
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
            $message->setExpediteur($this->getUser());
            $message->setCreatedAt(new \DateTimeImmutable());
            $messageRepository->add($message, true);

            return $this->redirectToRoute('boutique_messagerie_message', ['id' => $conversation->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/boutique/messagerie/show.html.twig', [
            'message' => $message,
            'form' => $form,
            'conversation' => $conversation
        ]);
    }
}

