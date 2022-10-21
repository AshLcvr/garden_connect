<?php

namespace App\Service;

use App\Entity\Annonce;
use App\Entity\Boutique;
use App\Entity\Category;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use Gumlet\ImageResize;
use App\Entity\ImagesHero;
use App\Entity\ImagesAnnonces;
use App\Entity\ImagesBoutique;
use App\Repository\AnnonceRepository;
use App\Repository\BoutiqueRepository;
use App\Repository\ImagesHeroRepository;
use App\Repository\ImagesAnnoncesRepository;
use App\Repository\ImagesBoutiqueRepository;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use function Symfony\Component\Mime\cc;

class UploadImage
{
    private $uploadDirectory;
    private $slugger;
    private $imagesBoutiqueRepository;
    private $boutiqueRepository;
    private $imagesAnnoncesRepository;
    private $annonceRepository;
    private $imagesHeroRepository;
    private $userRepository;
    private $categoryRepository;

    public function __construct(
        $uploadDirectory,
        SluggerInterface $slugger,
        ImagesBoutiqueRepository $imagesBoutiqueRepository,
        BoutiqueRepository $boutiqueRepository,
        ImagesAnnoncesRepository $imagesAnnoncesRepository,
        AnnonceRepository $annonceRepository,
        UserRepository $userRepository,
        ImagesHeroRepository $imagesHeroRepository,
        CategoryRepository $categoryRepository
    )
    {
        $this->uploadDirectory          = $uploadDirectory;
        $this->slugger                  = $slugger;
        $this->imagesBoutiqueRepository = $imagesBoutiqueRepository;
        $this->boutiqueRepository       = $boutiqueRepository;
        $this->imagesAnnoncesRepository = $imagesAnnoncesRepository;
        $this->annonceRepository        = $annonceRepository;
        $this->userRepository           = $userRepository;
        $this->imagesHeroRepository     = $imagesHeroRepository;
        $this->categoryRepository       = $categoryRepository;

    }

    public function uploadAndResizeImage(array $files, $entity)
    {
        $entity_class = get_class($entity);
        $id           = $entity->getId();

        foreach($files as $file)
        {
            // Renommage du fichier + set name en BDD
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename     = $this->slugger->slug($originalFilename);
            $fileName         = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

            if ($entity_class == Boutique::class){
                $image    = new ImagesBoutique();
                $boutique = $this->boutiqueRepository->find($id);
                $image->setTitle($fileName);
                // Set Boutique
                $image->setBoutique($boutique);
                // Flush
                $this->imagesBoutiqueRepository->add($image, true);
            }
            else if ($entity_class == Annonce::class) {
                $image   = new ImagesAnnonces();
                $annonce = $this->annonceRepository->find($id);
                $image->setTitle($fileName);
                // Set Annonces
                $image->setAnnonce($annonce);
                // Flush
                $this->imagesAnnoncesRepository->add($image, true);
            }
            else if ($entity_class == User::class ) {
                $user = $this->userRepository->find($id);
                $user->setImage($fileName);
            }
            else if ($entity_class == ImagesHero::class ) {
                $entity->setTitle($fileName);
                $this->imagesHeroRepository->add($entity, true);
            }
            else if ($entity_class == Category::class ) {
                $entity->setImage($fileName);
                $this->categoryRepository->add($entity, true);
            }

            // Déplacement vers dossier uploads
            $file->move($this->getUploadDirectory(), $fileName);
            unset($file);

            // Resize des images
            $miniature = new ImageResize($this->getUploadDirectory() .'/'. $fileName);
            if ($entity_class == Boutique::class){
                // Images boutique publique
                $miniature->crop(1400, 400 );
                $miniature->save($this->getUploadDirectory() .'/boutique/'. $fileName);
            }
            else if ($entity_class == Annonce::class) {
                // Miniature
                $miniature->crop(200, 200, true,ImageResize::CROPCENTER);
                $miniature->save($this->getUploadDirectory() .'/mini/'. $fileName);
                // Images annonces
                $miniature->resizeToBestFit(500, 500);
                $miniature->save($this->getUploadDirectory() .'/annonce/'. $fileName);
            }
            else if ($entity_class == User::class ) {
                // Profile Picture
                $miniature->crop(100, 100);
                $miniature->save($this->getUploadDirectory() .'/profile/'. $fileName);
            }
            else if ($entity_class == ImagesHero::class) {
                $miniature->crop(1400, 500, true, ImageResize::CROPCENTER);
                $miniature->save($this->getUploadDirectory() . '/hero/' . $fileName);
            }
            else if ($entity_class == Category::class) {
                // Resize de l'image (optionnel)
                $miniature->crop(240, 140);
                $miniature->save($this->getUploadDirectory() .'/category/'. $fileName);
            }

            // Suppression de l'originale
            $file_path = $this->getUploadDirectory() . '/' . $fileName;
            if (file_exists($file_path)) unlink($file_path);
        }
    }

    public function getUploadDirectory()
    {
        return $this->uploadDirectory;
    }
}