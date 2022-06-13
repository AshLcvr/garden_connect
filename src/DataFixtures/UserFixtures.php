<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    
    private UserPasswordHasherInterface $hasher;
    

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $adminPolo = new User();
        $adminPolo->setName('paul');
        $adminPolo->setSurname('joret');
        $adminPolo->isIsVerified();
        $adminPolo->setEmail('paul.joret@hotmail.fr');
        $adminPolo->setRoles(['ROLE_USER','ROLE_VENDEUR','ROLE_ADMIN','ROLE_SUPER_ADMIN']);
        $adminPolo->setActif(true);
        $adminPolo->setCreatedAt(new \DateTimeImmutable('-2 week'));
        $password = $this->hasher->hashPassword($adminPolo, 'michel');
        $adminPolo->setPassword($password);

        $manager->persist($adminPolo);

        $adminSacha = new User();
        $adminSacha->setName('sacha');
        $adminSacha->setSurname('lechevallier');
        $adminSacha->isIsVerified();
        $adminSacha->setEmail('sacha.lcvr@gmail.com');
        $adminSacha->setRoles(['ROLE_USER','ROLE_VENDEUR','ROLE_ADMIN','ROLE_SUPER_ADMIN']);
        $adminSacha->setActif(true);
        $adminSacha->setCreatedAt(new \DateTimeImmutable('-2 week'));
        $password = $this->hasher->hashPassword($adminSacha, 'michel');
        $adminSacha->setPassword($password);

        $manager->persist($adminSacha);

        $adminOrianne = new User();
        $adminOrianne->setName('orianne');
        $adminOrianne->setSurname('cielat');
        $adminOrianne->isIsVerified();
        $adminOrianne->setEmail('orianne.cielat@gmail.com');
        $adminOrianne->setRoles(['ROLE_USER','ROLE_VENDEUR','ROLE_ADMIN','ROLE_SUPER_ADMIN']);
        $adminOrianne->setActif(true);
        $adminOrianne->setCreatedAt(new \DateTimeImmutable('-2 week'));
        $password = $this->hasher->hashPassword($adminOrianne, 'michel');
        $adminOrianne->setPassword($password);

        $manager->persist($adminOrianne);

        for ($i = 0;$i <= 3;$i++) {
            $user = new User();
            $user->setName('user_'.$i);
            $user->setSurname('userSur_'.$i);
            $user->isIsVerified();
            $user->setEmail('user_'.$i.'@gmail.com');
            $user->setRoles(['ROLE_USER']);
            $user->setActif(true);
            $user->setCreatedAt(new \DateTimeImmutable());
            $password = $this->hasher->hashPassword($user, 'user_'.$i);
            $user->setPassword($password);
            $this->addReference('user_'.$i, $user);
            $manager->persist($user);
        }

        for ($i = 0;$i <= 3;$i++) {
            $user = new User();
            $user->setName('vendeur_'.$i);
            $user->setSurname('vendeurSur'.$i);
            $user->isIsVerified();
            $user->setEmail('vendeur_'.$i.'@gmail.com');
            $user->setRoles(['ROLE_USER','ROLE_VENDEUR']);
            $user->setActif(true);
            $user->setCreatedAt(new \DateTimeImmutable());
            $password = $this->hasher->hashPassword($user, 'vendeur_'.$i);
            $user->setPassword($password);
            $this->addReference('vendeur_'.$i, $user);
            $manager->persist($user);
        }

        $manager->flush();
        
        $this->addReference('polo', $adminPolo);
        $this->addReference('sacha', $adminSacha);
        $this->addReference('orianne', $adminOrianne);
        
    }
}
