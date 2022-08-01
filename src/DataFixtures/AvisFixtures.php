<?php

namespace App\DataFixtures;

use App\Entity\Avis;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AvisFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            UserFixtures::class,
            BoutiqueFixtures::class
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $avis1 = (new Avis())
            ->setTitle('Je recommande !')
            ->setCommentaire('J\'ai acheté de la du maïs chez Orianne et il était excellent!')
            ->setRating(5)
            ->setUser($this->getReference('sacha'))
            ->setBoutique($this->getReference('boutique_orianne'))
            ->setCreatedAt(new \DateTimeImmutable())
            ->setActif(1);
        $manager->persist($avis1);


        $avis2 = (new Avis())
            ->setTitle('Pas mal ..')
            ->setCommentaire('Bon produit mais j\'ai été malade le lendemain')
            ->setRating(4)
            ->setUser($this->getReference('polo'))
            ->setBoutique($this->getReference('boutique_orianne'))
            ->setCreatedAt(new \DateTimeImmutable())
            ->setActif(1);
        $manager->persist($avis2);

        $avis3 = (new Avis())
            ->setTitle('Moyen !')
            ->setCommentaire('Produit fade mais personne aimable!')
            ->setRating(3)
            ->setUser($this->getReference('vendeur_1'))
            ->setBoutique($this->getReference('boutique_orianne'))
            ->setCreatedAt(new \DateTimeImmutable())
            ->setActif(1);
        $manager->persist($avis3);

        $avis4 = (new Avis())
            ->setTitle('Pas mal ..')
            ->setCommentaire('Bon produit mais j\'ai été malade le lendemain')
            ->setRating(4)
            ->setUser($this->getReference('polo'))
            ->setBoutique($this->getReference('boutique_sacha'))
            ->setCreatedAt(new \DateTimeImmutable())
            ->setActif(1);
        $manager->persist($avis4);

        $avis5 = (new Avis())
            ->setTitle('super mal ..')
            ->setCommentaire('Bon produit mais j\'ai été malade le lendemain')
            ->setRating(4)
            ->setUser($this->getReference('polo'))
            ->setBoutique($this->getReference('boutique_sacha'))
            ->setCreatedAt(new \DateTimeImmutable())
            ->setActif(1);
        $manager->persist($avis5);

        $avis6 = (new Avis())
            ->setTitle('super mal dede ..')
            ->setCommentaire('Bon produit mais j\'ai été malade le lendemain')
            ->setRating(4)
            ->setUser($this->getReference('polo'))
            ->setBoutique($this->getReference('boutique_sacha'))
            ->setCreatedAt(new \DateTimeImmutable())
            ->setActif(1);
        $manager->persist($avis6);

        $avis7 = (new Avis())
            ->setTitle('Pas foufou !')
            ->setCommentaire('Ses radis étaient vendus moisis, je ne recommande pas !')
            ->setRating(2)
            ->setUser($this->getReference('vendeur_2'))
            ->setBoutique($this->getReference('boutique_orianne'))
            ->setCreatedAt(new \DateTimeImmutable())
            ->setActif(1);
        $manager->persist($avis7);

        $avis8 = (new Avis())
            ->setTitle('Bien mais pas extraordinaire...')
            ->setCommentaire('Pas mal pour un amateur !')
            ->setRating(3)
            ->setUser($this->getReference('orianne'))
            ->setBoutique($this->getReference('boutique_polo'))
            ->setCreatedAt(new \DateTimeImmutable())
            ->setActif(1);
        $manager->persist($avis8);

        $avis9 = (new Avis())
            ->setTitle('Passable')
            ->setCommentaire('Vendeur agréable mais en retard au RDV !')
            ->setRating(3)
            ->setUser($this->getReference('orianne'))
            ->setBoutique($this->getReference('boutique_sacha'))
            ->setCreatedAt(new \DateTimeImmutable())
            ->setActif(1);
        $manager->persist($avis9);

        $avis10 = (new Avis())
            ->setTitle('Excellent')
            ->setCommentaire('Des produits frais et avec du goût !')
            ->setRating(3)
            ->setUser($this->getReference('orianne'))
            ->setBoutique($this->getReference('boutique_2'))
            ->setCreatedAt(new \DateTimeImmutable())
            ->setActif(1);
        $manager->persist($avis10);

        $manager->flush();
    }
}
