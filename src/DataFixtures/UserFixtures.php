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
        $admin->setEmail('admin@admin.fr');
        $admin->setRoles(['ROLE_USER','ROLE_VENDEUR','ROLE_ADMIN','ROLE_SUPER_ADMIN']);
        $admin->setActif(true);
        $admin->setCreatedAt(new \DateTimeImmutable('-2 week'));
        $password = $this->hasher->hashPassword($admin, 'admin');
        $admin->setPassword($password);
        $this->addReference('admin', $admin);
        $manager->persist($admin);
        
        $boutique_test = new User();
        $boutique_test->setName('Test');
        $boutique_test->setSurname('Boutique');
        $boutique_test->isIsVerified();
        $boutique_test->setEmail('vendeur@vendeur.fr');
        $boutique_test->setRoles(['ROLE_VENDEUR']);
        $boutique_test->setActif(true);
        $boutique_test->setCreatedAt(new \DateTimeImmutable('-1 week'));
        $password = $this->hasher->hashPassword($boutique_test, 'vendeur');
        $boutique_test->setPassword($password);
        $this->addReference('vendeur_0', $boutique_test);
        $manager->persist($boutique_test);
        
        $user = new User();
        $user->setName('Alain');
        $user->setSurname('Connu');
        $user->isIsVerified();
        $user->setEmail('user@user.fr');
        $user->setRoles(['ROLE_USER']);
        $user->setActif(true);
        $user->setCreatedAt(new \DateTimeImmutable('-3 day'));
        $password = $this->hasher->hashPassword($user, 'user');
        $user->setPassword($password);
        $this->addReference('user_0', $user);
        $manager->persist($user);

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
