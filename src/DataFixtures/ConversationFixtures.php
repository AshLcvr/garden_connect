<?php

namespace App\DataFixtures;

use App\Entity\Conversation;
use App\Entity\Message;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ConversationFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $convPolo1 = new Conversation();
        $convPolo1->setPremierMessage('convPolo1');
        $convPolo1->setCreatedAt(new \DateTimeImmutable('-1 week'));
        $convPolo1->setIsRead(false);
        $convPolo1->setUser($this->getReference('polo'));
        $convPolo1->setCorrespondant($this->getReference('orianne'));
        $manager->persist($convPolo1);

        $message1ConvPolo1 = new Message();
        $message1ConvPolo1->setExpediteur($this->getReference('polo'));
        $message1ConvPolo1->setConversation($convPolo1);
        $message1ConvPolo1->setMessage('message1ConvPolo1');
        $message1ConvPolo1->setIsRead(false);
        $message1ConvPolo1->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($message1ConvPolo1);

        $convPolo2 = new Conversation();
        $convPolo2->setPremierMessage('convPolo2');
        $convPolo2->setCreatedAt(new \DateTimeImmutable('-4 days'));
        $convPolo2->setIsRead(false);
        $convPolo2->setUser($this->getReference('polo'));
        $convPolo2->setCorrespondant($this->getReference('sacha'));
        $manager->persist($convPolo2);

        $message1ConvPolo2 = new Message();
        $message1ConvPolo2->setExpediteur($this->getReference('polo'));
        $message1ConvPolo2->setConversation($convPolo2);
        $message1ConvPolo2->setMessage('message1ConvPolo2');
        $message1ConvPolo2->setIsRead(false);
        $message1ConvPolo2->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($message1ConvPolo2);

        $message2ConvPolo2 = new Message();
        $message2ConvPolo2->setExpediteur($this->getReference('polo'));
        $message2ConvPolo2->setConversation($convPolo2);
        $message2ConvPolo2->setMessage('message2ConvPolo2');
        $message2ConvPolo2->setIsRead(false);
        $message2ConvPolo2->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($message2ConvPolo2);

        $convSacha1 = new Conversation();
        $convSacha1->setPremierMessage('convSacha1');
        $convSacha1->setCreatedAt(new \DateTimeImmutable('-1 week'));
        $convSacha1->setIsRead(false);
        $convSacha1->setUser($this->getReference('sacha'));
        $convSacha1->setCorrespondant($this->getReference('polo'));
        $manager->persist($convSacha1);

        $message1ConvSacha1 = new Message();
        $message1ConvSacha1->setExpediteur($this->getReference('sacha'));
        $message1ConvSacha1->setConversation($convSacha1);
        $message1ConvSacha1->setMessage('message1ConvSacha1');
        $message1ConvSacha1->setIsRead(false);
        $message1ConvSacha1->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($message1ConvSacha1);

        $convSacha2 = new Conversation();
        $convSacha2->setPremierMessage('convSacha2');
        $convSacha2->setCreatedAt(new \DateTimeImmutable('-4 days'));
        $convSacha2->setIsRead(false);
        $convSacha2->setUser($this->getReference('sacha'));
        $convSacha2->setCorrespondant($this->getReference('orianne'));
        $manager->persist($convSacha2);

        $message1ConvSacha2 = new Message();
        $message1ConvSacha2->setExpediteur($this->getReference('sacha'));
        $message1ConvSacha2->setConversation($convSacha2);
        $message1ConvSacha2->setMessage('message1ConvSacha2');
        $message1ConvSacha2->setIsRead(false);
        $message1ConvSacha2->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($message1ConvSacha2);

        $message2ConvSacha2 = new Message();
        $message2ConvSacha2->setExpediteur($this->getReference('sacha'));
        $message2ConvSacha2->setConversation($convSacha2);
        $message2ConvSacha2->setMessage('message2ConvSacha2');
        $message2ConvSacha2->setIsRead(false);
        $message2ConvSacha2->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($message2ConvSacha2);

        $convOrianne1 = new Conversation();
        $convOrianne1->setPremierMessage('convOrianne1');
        $convOrianne1->setCreatedAt(new \DateTimeImmutable('-1 week'));
        $convOrianne1->setIsRead(false);
        $convOrianne1->setUser($this->getReference('orianne'));
        $convOrianne1->setCorrespondant($this->getReference('sacha'));
        $manager->persist($convOrianne1);

        $message1ConvOrianne1 = new Message();
        $message1ConvOrianne1->setExpediteur($this->getReference('orianne'));
        $message1ConvOrianne1->setConversation($convOrianne1);
        $message1ConvOrianne1->setMessage('message1ConvOrianne1');
        $message1ConvOrianne1->setIsRead(false);
        $message1ConvOrianne1->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($message1ConvOrianne1);

        $convOrianne2 = new Conversation();
        $convOrianne2->setPremierMessage('convOrianne2');
        $convOrianne2->setCreatedAt(new \DateTimeImmutable('-4 days'));
        $convOrianne2->setIsRead(false);
        $convOrianne2->setUser($this->getReference('orianne'));
        $convOrianne2->setCorrespondant($this->getReference('polo'));
        $manager->persist($convOrianne2);

        $message1ConvOrianne2 = new Message();
        $message1ConvOrianne2->setExpediteur($this->getReference('orianne'));
        $message1ConvOrianne2->setConversation($convOrianne2);
        $message1ConvOrianne2->setMessage('message1ConvOrianne2');
        $message1ConvOrianne2->setIsRead(false);
        $message1ConvOrianne2->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($message1ConvOrianne2);

        $message2ConvOrianne2 = new Message();
        $message2ConvOrianne2->setExpediteur($this->getReference('orianne'));
        $message2ConvOrianne2->setConversation($convOrianne2);
        $message2ConvOrianne2->setMessage('message2ConvSacha2');
        $message2ConvOrianne2->setIsRead(false);
        $message2ConvOrianne2->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($message2ConvOrianne2);

        for ($i = 0;$i <= 3;$i++) { 
            $conv = new Conversation();
            $conv->setPremierMessage('convUser_'.$i);
            $conv->setCreatedAt(new \DateTimeImmutable('-2 weeks'));
            $conv->setIsRead(false);
            $conv->setUser($this->getReference('user_'.$i));
            $conv->setCorrespondant($this->getReference('polo'));
            $manager->persist($conv);
        }

        for ($i = 0;$i <= 3;$i++) { 
            $conv = new Conversation();
            $conv->setPremierMessage('convVendeur_'.$i);
            $conv->setCreatedAt(new \DateTimeImmutable());
            $conv->setIsRead(false);
            $conv->setUser($this->getReference('vendeur_'.$i));
            $conv->setCorrespondant($this->getReference('polo'));
            $manager->persist($conv);
        }

        for ($i = 0;$i <= 3;$i++) { 
            $conv = new Conversation();
            $conv->setPremierMessage('convUser_'.$i);
            $conv->setCreatedAt(new \DateTimeImmutable('-2 weeks'));
            $conv->setIsRead(false);
            $conv->setUser($this->getReference('user_'.$i));
            $conv->setCorrespondant($this->getReference('sacha'));
            $manager->persist($conv);
        }

        for ($i = 0;$i <= 3;$i++) { 
            $conv = new Conversation();
            $conv->setPremierMessage('convVendeur_'.$i);
            $conv->setCreatedAt(new \DateTimeImmutable());
            $conv->setIsRead(false);
            $conv->setUser($this->getReference('vendeur_'.$i));
            $conv->setCorrespondant($this->getReference('sacha'));
            $manager->persist($conv);
        }

        for ($i = 0;$i <= 3;$i++) { 
            $conv = new Conversation();
            $conv->setPremierMessage('convUser_'.$i);
            $conv->setCreatedAt(new \DateTimeImmutable('-2 weeks'));
            $conv->setIsRead(false);
            $conv->setUser($this->getReference('user_'.$i));
            $conv->setCorrespondant($this->getReference('orianne'));
            $manager->persist($conv);
        }

        for ($i = 0;$i <= 3;$i++) { 
            $conv = new Conversation();
            $conv->setPremierMessage('convVendeur_'.$i);
            $conv->setCreatedAt(new \DateTimeImmutable());
            $conv->setIsRead(false);
            $conv->setUser($this->getReference('vendeur_'.$i));
            $conv->setCorrespondant($this->getReference('orianne'));
            $manager->persist($conv);
        }

        $manager->flush();
    }
}
