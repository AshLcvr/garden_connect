<?php

namespace App\DataFixtures;

use App\Repository\BoutiqueRepository;
use App\Repository\SubcategoryRepository;
use App\Service\CallApi;
use DateTimeImmutable;
use App\Entity\Annonce;
use App\Entity\ImagesAnnonces;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AnnonceFixtures extends Fixture implements DependentFixtureInterface
{
    private $boutiqueRepository;
    private $subcategoryRepository;
    private $callApi;

    public function __construct(
        BoutiqueRepository $boutiqueRepository,
        SubcategoryRepository $subcategoryRepository,
        CallApi $callApi
    ){
        $this->boutiqueRepository    = $boutiqueRepository;
        $this->subcategoryRepository = $subcategoryRepository;
        $this->callApi               = $callApi;
    }

    public function getDependencies()
    {
        return [
            BoutiqueFixtures::class,
            CategoriesFixtures::class
        ];
    }

    public function load(ObjectManager $manager): void
    {
        // Création d'annonces fictives via Faker
        $allBoutiques = $this->boutiqueRepository->findAll();
        foreach ($allBoutiques as $boutique){
            for($i = 0; $i <= random_int(2,5) ; $i++) {
                $randSubcat = $this->subcategoryRepository->randomSubcategory();

                $annonce = new Annonce();
                $annonce->setTitle($randSubcat->getTitle());
                $annonce->setDescription($this->callApi->generateLipsumusingAsdfast(6,25))
                    ->setPrice(random_int(1, 10))
                    ->setMesure($this->getReference('Kg'))
                    ->setSubcategory($randSubcat)
                    ->setBoutique($boutique)
                    ->setActif(true)
                    ->setCreatedAt(new DateTimeImmutable('-2 weeks'));
                for ($i = 1; $i < random_int(2,4); $i++){
                    $imageAnnonce = (new ImagesAnnonces())
                        ->setTitle($this->setRandomAnnonceImage($randSubcat->getTitle()));
                    $annonce->addImagesAnnonce($imageAnnonce);
                    $manager->persist($imageAnnonce);
                }
                $manager->persist($annonce);
            }
        }
        $manager->flush();
    }

    public function setRandomAnnonceImage($subcat)
    {
        $annonceImageArray = [
            'Amande'       => ['31-almond-83766-1280-6321becf33889.jpg', '31-almond-3523569-1920-6321becf6aeaa.jpg','31-almonds-1768792-1920-6321becfca69a.jpg','31-fruits-183412-1920-6321becec601b.jpg'],
            'Pêche'        => ['31-peach-1074997-1920-6321c151e4fea.jpg', '31-peach-2721852-1920-6321c1531a457.jpg','31-peaches-379893-1920-6321c152496d6.jpg','31-peaches-2573836-1920-6321c152b3ab5.jpg'],
            'Raisin noir'  => ['31-grape-2749369-1920-6321c22c38b39.jpg', '31-grapes-2104075-1920-6321c22b889f8.jpg','31-grapes-522010-1920-6321c22bd39d9.jpg','31-grape-7423695-1920-6321c22c90118.jpg'],
            'Œuf de poule' => ['31-egg-2580904-1920-6321c2c83f4bb.jpg', '31-food-316412-1280-6321c2c80bf5e.jpg','31-eggs-2048476-1920-6321c2c79b84f.jpg','31-eggs-1374141-1920-6321c2c89ba4a.jpg'],
            'Citrouilles'  => ['31-harvest-4454745-1920-6321c3300e29f.jpg', '31-vegetable-2855925-1920-6321c32f4047f.jpg','31-pumpkins-3636243-1920-6321c32eda2d5.jpg','31-pumpkins-1632786-1920-6321c32fa5a9b.jpg'],
            'Oignon'       => ['31-onions-1397037-1920-6321c3a94728b.jpg', '31-onion-3706937-1920-6321c3a88976c.jpg','31-onion-3540502-1920-6321c3a8e2322.jpg','31-onion-1144620-1280-6321c3a858554.jpg'],
            'Pistache'     => ['31-pistachios-1540123-1920-6321c3fa421c2.jpg', '31-snack-1025396-1920-6321c3f9c0239.jpg','31-nuts-428544-1920-6321c3fae18d7.jpg','31-pistachios-3223610-1920-6321c3fb5a632.jpg'],
            'Noisette'     => ['31-hazelnuts-3783066-1920-6321c4aa7ef7e.jpg', '31-hazelnuts-3752184-1920-6321c4ab15dbc.jpg','31-hazelnuts-1707601-1920-6321c4ab6f15c.jpg','31-hazelnuts-73939-1920-6321c4abbf37b.jpg'],
            'Fraise'       => ['31-strawberry-5079237-1920-6321c4ffa377e.jpg', '31-strawberry-1180048-1920-6321c50008912.jpg','31-strawberries-3431122-1920-6321c50063209.jpg','31-strawberries-2960533-1920-6321c4ff438bf.jpg'],
            'Framboise'    => ['31-raspberry-2635886-1920-6321c540705e6.jpg', '31-raspberry-1503998-1920-6321c5413332c.jpg','31-raspberries-2431029-1920-6321c54017df0.jpg','31-raspberries-2023404-1920-6321c540cafb6.jpg'],
            'Melon'        => ['31-cantaloupe-1262199-1920-6321c5a56327b.jpg', '31-melon-3433835-1920-6321c5a6583ca.jpg','31-melon-1631569-1920-6321c5a509491.jpg','31-melon-626351-1920-6321c5a5c1606.jpg'],
            'Poire'        => ['31-pear-7360921-1920-6321c5f83c8b6.jpg', '31-pear-3560106-1920-6321c5f8a2518.jpg','31-pear-3519397-1920-6321c5f9153e2.jpg','31-pear-1084687-1920-6321c5f780750.jpg'],
            'Pomme'        => ['31-apple-2788616-1920-6321c64894f0c.jpg', '31-apple-2720105-1920-6321c647d3815.jpg','31-apple-1589869-1920-6321c6483b180.jpg','31-apple-1532055-1920-6321c648e2956.jpg'],
            'Blé'          => ['31-spike-8743-1920-6321c6ad5a1f6.jpg', '31-wheat-865152-1920-6321c6ad03122.jpg','31-wheat-381848-1920-6321c6adbe3b3.jpg','31-wheat-8762-1920-6321c6ac8d82e.jpg'],
            'Menthe'       => ['31-peppermint-4487398-1920-6321c721a0914.jpg', '31-moroccan-mint-2396530-1920-6321c721151e3.jpg','31-mint-1500452-1920-6321c720b6afa.jpg','31-mint-1433826-1920-6321c7205b276.jpg'],
            'Muscade'      => ['31-nutmeg-441645-1920-6321c7ab7a112.jpg', '31-nutmeg-2427844-1920-6321c7abe63f0.jpg','31-coconut-5468986-1920-6321c7ab1ac78.jpg'],
            'Persil'       => ['31-parsley-1665402-1920-6321c81a7f3e9.jpg', '31-parsley-741996-1920-6321c81a19ddc.jpg','31-parsley-261039-1920-6321c81ad52a5.jpg','31-garden-774076-1920-6321c819b391b.jpg'],
            'Rhubarbe'     => ['31-rhubarb-5155214-1920-6321c86267718.jpg', '31-rhubarb-839618-1920-6321c862be1fe.jpg','31-rhubarb-318217-1920-6321c8637d825.jpg','31-rheum-791719-1920-6321c8632399d.jpg'],
            'Miel'         => ['31-honey-5043708-1920-6321c8bf17141.jpg', '31-honey-2542952-1920-6321c8beb302e.jpg','31-honey-1958464-1920-6321c8be3d3ac.jpg','31-honey-823614-1920-6321c8bf68462.jpg'],
            'Champignon'   => ['31-mushrooms-5683658-1920-6321c97fbaf7b.jpg', '31-mushrooms-1623893-1920-6321c97f5c012.jpg','31-mushrooms-523629-1920-6321c97ef4037.jpg','31-fungus-1194380-1920-6321c9801d441.jpg'],
            'Carottes'     => ['31-carrots-2387394-1920-6321c9b547182.jpg', '31-carrots-1508847-1920-6321c9b4b658e.jpg','31-carrots-673184-1920-6321c9b3e34e5.jpg','31-carrot-1565597-1920-6321c9b4566ab.jpg'],
            'Haricots'     => ['31-green-beans-1018624-1920-6321c9ff9e11b.jpg', '31-beans-7300846-1920-6321c9fedd3e3.jpg','31-beans-3688585-1920-6321c9ff42877.jpg','31-beans-598185-1920-6321c9fe72fb3.jpg'],
            'Manioc'       => ['31-cassava-5578528-1920-6321ca77937b6.jpg', '31-yuca-1353258-1920-6321ca793925c.jpg','31-manioc-892517-1920-6321ca788777d.jpg','31-manioc-285033-1920-6321ca7802ab2.jpg'],
            'Poireau'      => ['31-spring-onions-845032-1920-6321cad3a8ab7.jpg', '31-vegetables-1939663-1920-6321cad346a74.jpg','31-leeks-673243-1920-6321cad2dfcd1.jpg','31-leek-2199563-1920-6321cad40bbab.jpg'],
            'Tomate'       => ['31-tomatoes-1280859-1280-6321cb34c3f65.jpg', '31-tomatoes-5566744-1920-6321cb34369bf.jpg','31-tomatoes-5566741-1920-6321cb33c7f98.jpg','31-tomatoes-1561565-1920-6321cb3518ee5.jpg']
        ];
        $subCatImages = $annonceImageArray[$subcat];
        $imageIndex   = array_rand($subCatImages);
        $imageTitle   = $subCatImages[$imageIndex];

        return $imageTitle;
    }
}
