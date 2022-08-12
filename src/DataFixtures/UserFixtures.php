<?php

namespace App\DataFixtures;

use App\Entity\User;
use Faker;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use function Symfony\Component\String\indexOf;

/**
 * Class UserFixtures
 * @package App\DataFixtures
 */
class UserFixtures extends Fixture
{

    /**
     * @var UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $hasher;


    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $adminPolo = new User();
        $adminPolo->setName('Joret');
        $adminPolo->setSurname('Paul');
        $adminPolo->isIsVerified();
        $adminPolo->setEmail('paul.joret@hotmail.fr');
        $adminPolo->setRoles(['ROLE_USER','ROLE_VENDEUR','ROLE_ADMIN','ROLE_SUPER_ADMIN']);
        $adminPolo->setActif(true);
        $adminPolo->setCreatedAt(new \DateTimeImmutable('-2 week'));
        $password = $this->hasher->hashPassword($adminPolo, 'michel');
        $adminPolo->setPassword($password);
        $this->addReference('polo', $adminPolo);
        $manager->persist($adminPolo);

        $adminSacha = new User();
        $adminSacha->setName('Lechevallier');
        $adminSacha->setSurname('Sacha');
        $adminSacha->isIsVerified();
        $adminSacha->setEmail('sacha.lcvr@gmail.com');
        $adminSacha->setRoles(['ROLE_USER','ROLE_VENDEUR','ROLE_ADMIN','ROLE_SUPER_ADMIN']);
        $adminSacha->setActif(true);
        $adminSacha->setCreatedAt(new \DateTimeImmutable('-2 week'));
        $password = $this->hasher->hashPassword($adminSacha, 'michel');
        $adminSacha->setPassword($password);
        $this->addReference('sacha', $adminSacha);
        $manager->persist($adminSacha);

        $adminOrianne = new User();
        $adminOrianne->setName('Cielat');
        $adminOrianne->setSurname('Orianne');
        $adminOrianne->isIsVerified();
        $adminOrianne->setEmail('orianne.cielat@gmail.com');
        $adminOrianne->setRoles(['ROLE_USER','ROLE_VENDEUR','ROLE_ADMIN','ROLE_SUPER_ADMIN']);
        $adminOrianne->setActif(true);
        $adminOrianne->setCreatedAt(new \DateTimeImmutable('-2 week'));
        $password = $this->hasher->hashPassword($adminOrianne, 'michel');
        $adminOrianne->setPassword($password);
        $this->addReference('orianne', $adminOrianne);
        $manager->persist($adminOrianne);

        // Création d'utilisateurs factices via Faker
        $faker = Faker\Factory::create('fr_FR');
        $countVendeur =  0;
        $countUser    =  0;
        for ($i = 0; $i < 200; $i++) {
            $user = (new User())
            ->setName($faker->lastName)
            ->setSurname($faker->firstName)
            ->setEmail($faker->email)
            ->setActif(true);
            $randomWeek = random_int(-10,-1);
            $user
            ->setCreatedAt(new \DateTimeImmutable($randomWeek.' week'));
            $password = $this->hasher->hashPassword($user, $faker->password);
            $user->setPassword($password);
            // Attribution d'un rôle aléatoire
            $roles = [['ROLE_USER','ROLE_VENDEUR'],['ROLE_USER']];
            $indexRandRoles = array_rand($roles);
            $user->setRoles($roles[$indexRandRoles]);
            if ($indexRandRoles === 0){
                $countVendeur += 1;
                $userRole = 'vendeur_'.$countVendeur ;
                $this->addReference($userRole, $user);
            }else{
                $countUser +=  1;
                $userRole = 'user_'.$countUser;
                $this->addReference($userRole, $user);
            }
            $manager->persist($user);
        }

        $manager->flush();
    }
}
