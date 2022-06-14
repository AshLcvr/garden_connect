<?php 

namespace App\Controller\Boutique;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessagerieBoutiqueController extends AbstractController
{

    #[Route('/boutique/messagerie', name: 'boutique_messagerie')]
    public function boutiqueMessagerie()
    {
        $user = $this->getUser();
        $conversationsCorresp = $user->getConversationsCorresp();
        $conversationsInit = $user->getConversationsInit();

        return $this->renderForm('front/boutique/messagerie/index.html.twig', [
            'conversationsCorresp' => $conversationsCorresp,
            'conversationsInit' => $conversationsInit
        ]);
    }

    #[Route('/boutique/messagerie/{id}', name: 'conversation_message')]
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