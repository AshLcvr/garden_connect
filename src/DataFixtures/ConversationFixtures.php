<?php

namespace App\DataFixtures;

use App\Entity\Conversation;
use App\Service\CallApi;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ConversationFixtures extends Fixture implements DependentFixtureInterface
{
    private $callApi;

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            BoutiqueFixtures::class
        ];
    }

    public function __construct(CallApi $callApi)
    {
        $this->callApi = $callApi;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1;$i <= 5;$i++) {
            $conv = new Conversation();
            $conv->setPremierMessage($this->callApi->generateLipsumusingAsdfast(3,10));
            $conv->setCreatedAt(new \DateTimeImmutable('-2 weeks'));
            $conv->setIsRead(false);
            $conv->setUser($this->getReference('user_'.$i));
            $conv->setCorrespondant($this->getReference('admin'));
            $manager->persist($conv);
        }

        for ($i = 1;$i <= 5;$i++) {
            $conv = new Conversation();
            $conv->setPremierMessage('Autem eum odio quas non debitis nisi');
            $conv->setCreatedAt(new \DateTimeImmutable());
            $conv->setIsRead(false);
            $conv->setUser($this->getReference('vendeur_'.$i));
            $conv->setCorrespondant($this->getReference('admin'));
            $manager->persist($conv);
        }

        $manager->flush();
    }
}
