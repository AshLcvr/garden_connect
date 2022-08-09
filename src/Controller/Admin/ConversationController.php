<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Message;
use App\Form\MessageType;
use App\Entity\Conversation;
use App\Form\ConversationType;
use App\Repository\AvisRepository;
use App\Repository\UserRepository;
use App\Repository\AnnonceRepository;
use App\Repository\MessageRepository;
use App\Repository\BoutiqueRepository;
use App\Repository\ConversationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
class ConversationController extends AbstractController
{
    #[Route('/conversation_moderation/admin', name: 'app_conversation_moderation')]
    public function conversation_moderation(Request $request, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();
        $nbrNonlus = 0;
        $nbrNonlusInit = [];
        $conversations = [];

        $conversationsInit = $user->getConversationsInit();
        foreach ($conversationsInit as $key => $conv) {
            $conversations[] = $conv;
            foreach ($conv->getMessages() as $key => $mess) {
                if ($mess->isIsRead() == false && $mess->getExpediteur()->getId() != $this->getUser()->getId()) {
                    $nbrNonlus += 1;
                }
            }
            $nbrNonlusInit[$conv->getId()] = $nbrNonlus;
            $nbrNonlus = 0;
        }
        usort($conversations, function(Conversation $a, Conversation $b){
            return $a->getModifiedAt()>$b->getModifiedAt()?-1:1;
        });
        $conversations = $this->maPagination($conversations, $paginator, $request, 5);

        return $this->renderForm('admin/conversation/conversation_moderation.html.twig', [
            'conversationsInit' => $conversations,
            'nbrNonlusInit' => $nbrNonlusInit
        ]);
    }

    #[Route('/conversation_demande_user/admin', name: 'app_conversation_demande_user')]
    public function conversation_demande_user(Request $request, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();
        $nbrNonlus = 0;
        $nbrNonlusCorresp = [];
        $conversations = [];

        $conversationsCorresp = $user->getConversationsCorresp();
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
            $nbrNonlusCorresp[$conv->getId()] = $nbrNonlus;
            $nbrNonlus = 0;
        }
        usort($conversations, function(Conversation $a, Conversation $b){
            return $a->getModifiedAt()>$b->getModifiedAt()?-1:1;
        });
        $conversations = $this->maPagination($conversations, $paginator, $request, 5);

        return $this->renderForm('admin/conversation/conversation_demande_user.html.twig', [
            'conversationsCorresp' => $conversations,
            'nbrNonlusCorresp' => $nbrNonlusCorresp
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
            $conversation->setModifiedAt(new \DateTimeImmutable());
            $conversation->setIsRead(false);
            $conversation->setUser($this->getUser());
            $conversation->setCorrespondant($user);
            $conversationRepository->add($conversation, true);

            return $this->redirectToRoute('app_conversation_index', [], Response::HTTP_SEE_OTHER);
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
            $conversation->setModifiedAt(new \DateTimeImmutable());
            $conversation->setUser($this->getUser());
            $conversation->setCorrespondant($user);
            $conversationRepository->add($conversation, true);

            return $this->redirectToRoute('app_conversation_index', [], Response::HTTP_SEE_OTHER);
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
            $conversation->setModifiedAt(new \DateTimeImmutable());
            $conversation->setUser($this->getUser());
            $conversation->setCorrespondant($user);
            $conversationRepository->add($conversation, true);

            return $this->redirectToRoute('app_conversation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/conversation/new.html.twig', [
            'conversation' => $conversation,
            'form' => $form,
        ]);
    }

    #[Route('/conversation/avis/{id}/{id_avis}', name: 'new_conversation_with_id_avis', requirements: ['id' => '\d+', 'id_boutique' => '\d+'])]
    public function newConversationWithIdAvis(Request $request, ConversationRepository $conversationRepository, User $user, $id_avis, AvisRepository $avisRepository): Response
    {
        $avis = $avisRepository->find($id_avis);
        // dd($user->getRoles());
        if (in_array("ROLE_VENDEUR", $user->getRoles(), true)) {
            $avisUrl = $this->generateUrl('app_avis_edit', ['id' => urlencode($avis->getId())], UrlGeneratorInterface::ABSOLUTE_URL);
        }
        else{
            $avisUrl = $this->generateUrl('profil_avis_edit', ['id' => urlencode($avis->getId())], UrlGeneratorInterface::ABSOLUTE_URL);
        }
        $conversation = new Conversation();
        $conversation->setPremierMessage($avisUrl);
        $form = $this->createForm(ConversationType::class, $conversation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $conversation->setCreatedAt(new \DateTimeImmutable());
            $conversation->setModifiedAt(new \DateTimeImmutable());
            $conversation->setUser($this->getUser());
            $conversation->setCorrespondant($user);
            $conversationRepository->add($conversation, true);

            return $this->redirectToRoute('app_conversation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/conversation/new.html.twig', [
            'conversation' => $conversation,
            'form' => $form,
        ]);
    }

    #[Route('/conversation/message/{id}', name: 'conversation_message')]
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

            return $this->redirectToRoute('conversation_message', ['id' => $conversation->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/conversation/message/index.html.twig', [
            'message' => $message,
            'form' => $form,
            'conversation' => $conversation
        ]);
    }

    public function notification(): Response
    {
        $user = $this->getUser();

        $nbrNonlus = 0;
        $conversationsCorresp = $user->getConversationsCorresp();
        foreach ($conversationsCorresp as $key => $conv) {
            if ($conv->isIsRead() === false) {
                $nbrNonlus = $nbrNonlus + 1;
            }
            foreach ($conv->getMessages() as $key => $mess) {
                if ($mess->isIsRead() === false && $mess->getExpediteur()->getId() != $this->getUser()->getId()) {
                    $nbrNonlus += 1;
                }
            }
        }

        $conversationsInit = $user->getConversationsInit();
        foreach ($conversationsInit as $key => $conv) {
            foreach ($conv->getMessages() as $key => $mess) {
                if ($mess->isIsRead() == false && $mess->getExpediteur()->getId() != $this->getUser()->getId()) {
                    $nbrNonlus += 1;
                }
            }
        }
        return $this->render('_partials/_notification.html.twig', [
            'nbrNonlus' => $nbrNonlus
        ]);
    }
    public function listing(ConversationRepository $conversationRepository, UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy([
            'id' => $this->getUser()->getId()
        ]);
        $listing = [];
        $nbrNonlus = 0;
        $tblNbrNonlus = [];


        $conversationsCorresp = $conversationRepository->findBy(
            [
            'correspondant' => $user,
            ],
            [
            'created_at' => 'ASC',
            ]
        );
        if (!empty($conversationsCorresp)) {
            foreach ($conversationsCorresp as $key => $value) {
                if ($value->isIsRead() === false) {
                    $listing[] = $value;
                    $nbrNonlus += 1;
                    foreach ($value->getMessages() as $key => $mess) {
                        if ($mess->isIsRead() === false && $mess->getExpediteur()->getId() != $user->getId()) {
                            $nbrNonlus += 1;
                        }
                    }
                }
                else {
                    foreach ($value->getMessages() as $key => $mess) {
                        if ($mess->isIsRead() === false && $mess->getExpediteur()->getId() != $user->getId()) {
                            $listing[] = $value;
                            $nbrNonlus += 1;
                        }
                    }
                }
                $tblNbrNonlus[$value->getId()] = $nbrNonlus;
                $nbrNonlus = 0;
            }
        } 

        $conversationsInit = $conversationRepository->findBy(
            [
            'user' => $user,
            ],
            [
            'created_at' => 'ASC',
            ],
        );
        if (!empty($conversationsInit)) {
            foreach ($conversationsInit as $key => $value) {
                foreach ($value->getMessages() as $key => $mess) {
                    if ($mess->isIsRead() === false && $mess->getExpediteur()->getId() != $value->getUser()->getId()) {
                        $listing[] = $value;
                        $nbrNonlus += 1;
                    }
                }
                $tblNbrNonlus[$value->getId()] = $nbrNonlus;
                $nbrNonlus = 0;
            }
        }
        
        if (!empty($listing)) {
            usort($listing, function(Conversation $a, Conversation $b){
                return $a->getCreatedAt()>$b->getCreatedAt()?-1:1;
            });

            $countListing = count($listing);

            $newListing = [];

            for ($i = 0; $i <= 4 ; $i++) { 
                $newListing[] = $listing[$i];
            }
            

            $countNewListing = count($newListing);
            $count = $countListing - $countNewListing;
        }
        else{
            $newListing = false;
            $count = false;
        }
        
        
        return $this->render('_partials/_listingMessagesNonLus.html.twig', [
            'newListing' => $newListing,
            'tblNbrNonlus' => $tblNbrNonlus,
            'count' => $count
        ]);
    }
}
