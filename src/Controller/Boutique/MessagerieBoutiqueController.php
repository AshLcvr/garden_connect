<?php 

namespace App\Controller\Boutique;

use App\Entity\Message;
use App\Form\MessageType;
use App\Entity\Conversation;
use App\Repository\MessageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        $conversations = [];

        foreach ($conversationsCorresp as $key => $value) {
            $conversations[] = $value;
        }
        foreach ($conversationsInit as $key => $value) {
            $conversations[] = $value;
        }

        return $this->renderForm('front/boutique/messagerie/index.html.twig', [
            'conversations' => $conversations,
        ]);
    }

    #[Route('/boutique/messagerie/{id}', name: 'boutique_messagerie_message')]
    public function newMessage(Request $request, MessageRepository $messageRepository, Conversation $conversation): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setConversation($conversation);
            $message->setExpediteur($this->getUser());
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