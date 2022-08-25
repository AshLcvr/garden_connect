<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Service\CallApi;
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
    private CallApi $callApi;


    public function __construct(UserPasswordHasherInterface $hasher, CallApi $callApi)
    {
        $this->hasher  = $hasher;
        $this->callApi = $callApi;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setName('Garden');
        $admin->setSurname('Connect');
        $admin->isIsVerified();
        $admin->setEmail('garden-connect@garden-connect.fr');
        $admin->setRoles(['ROLE_USER','ROLE_VENDEUR','ROLE_ADMIN','ROLE_SUPER_ADMIN']);
        $admin->setActif(true);
        $admin->setCreatedAt(new \DateTimeImmutable('-2 week'));
        $password = $this->hasher->hashPassword($admin, 'admin');
        $admin->setPassword($password);
        $this->addReference('admin', $admin);
        $manager->persist($admin);
        
        $adminPolo = new User();
        $adminPolo->setName('Paul');
        $adminPolo->setSurname('Joret');
        $adminPolo->isIsVerified();
        $adminPolo->setEmail('paul.joret@hotmail.fr');
        $adminPolo->setRoles(['ROLE_USER','ROLE_VENDEUR','ROLE_ADMIN','ROLE_SUPER_ADMIN']);
        $adminPolo->setActif(true);
        $adminPolo->setCreatedAt(new \DateTimeImmutable('-2 week'));
        $password = $this->hasher->hashPassword($adminPolo, 'michel');
        $adminPolo->setPassword($password);
        $this->addReference('polo', $adminPolo);
        $manager->persist($adminPolo);

        $userPolo = new User();
        $userPolo->setName('Paul');
        $userPolo->setSurname('Joret');
        $userPolo->isIsVerified();
        $userPolo->setEmail('paul2.joret@hotmail.fr');
        $userPolo->setRoles(['ROLE_USER']);
        $userPolo->setActif(true);
        $userPolo->setCreatedAt(new \DateTimeImmutable('-2 week'));
        $password = $this->hasher->hashPassword($userPolo, 'michel');
        $userPolo->setPassword($password);
        $manager->persist($userPolo);

        $adminSacha = new User();
        $adminSacha->setName('Sacha');
        $adminSacha->setSurname('Lechevallier');
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
        $adminOrianne->setName('Orianne');
        $adminOrianne->setSurname('Cielat');
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
        $countVendeur    =  0;
        $countUser       =  0;
        $randomWeek      = random_int(-10,-1);
        $genderArray     = ['male','female'];
        $randGenderIndex = array_rand($genderArray);

        for ($i = 0; $i < 30; $i++) {
            $user = new User();
            // Attribution d'un prénom et d'une photo de profile selon le genre
            if ($randGenderIndex === 0)
            {
                $user
                    ->setName($faker->firstNameMale)
                    ->setImage($this->callApi->generateRandomProfilePictureByGenderUsingRandomUser(0));
            }else{
                $user
                    ->setName($faker->firstNameFemale)
                    ->setImage($this->callApi->generateRandomProfilePictureByGenderUsingRandomUser(1));
            }
            $user
            ->setSurname($faker->lastName)
            ->setEmail($faker->email)
            ->setActif(true)
            ->setCreatedAt(new \DateTimeImmutable($randomWeek.' week'));
            $password = $this->hasher->hashPassword($user, $faker->password);
            $user->setPassword($password);
            // Attribution d'un rôle aléatoire
            $roles          = [['ROLE_VENDEUR'],['ROLE_USER']];
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
