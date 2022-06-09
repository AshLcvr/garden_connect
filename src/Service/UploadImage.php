<?php

namespace App\Service;

use App\Entity\Images;
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

class UploadImage
{
    private $uploadDirectory;
    private $slugger;
    private $imagesBoutiqueRepository;
    private $boutiqueRepository;
    private $imagesAnnoncesRepository;
    private $annonceRepository;
    private $imagesHeroRepository;

    public function __construct(
        $uploadDirectory,
        SluggerInterface $slugger,
        ImagesBoutiqueRepository $imagesBoutiqueRepository,
        BoutiqueRepository $boutiqueRepository,
        ImagesAnnoncesRepository $imagesAnnoncesRepository,
        AnnonceRepository $annonceRepository,
        ImagesHeroRepository $imagesHeroRepository
    )
    {
        $this->uploadDirectory          = $uploadDirectory;
        $this->slugger                  = $slugger;
        $this->imagesBoutiqueRepository = $imagesBoutiqueRepository;
        $this->boutiqueRepository       = $boutiqueRepository;
        $this->imagesAnnoncesRepository = $imagesAnnoncesRepository;
        $this->annonceRepository        = $annonceRepository;
        $this->imagesHeroRepository     = $imagesHeroRepository;

    }

    public function uploadBoutique(array $files, $boutique_id)
    {
        foreach($files as $file)
        {
            $image    = new ImagesBoutique();
            $boutique = $this->boutiqueRepository->find($boutique_id);

            // Renommage du fichier + set name en BDD
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename     = $this->slugger->slug($originalFilename);
            $fileName         = $boutique->getId().'-'.$safeFilename.'-'.uniqid().'.'.$file->guessExtension();
            $image->setTitle($fileName);
            // Set Boutique
            $image->setBoutique($boutique);
            // Flush
            $this->imagesBoutiqueRepository->add($image, true);

            // Déplacement vers dossier uploads
            $file->move($this->getUploadDirectory(), $fileName);
            unset($file);

            // Resize de l'image (optionnel)
            // Miniature
            $miniature = new ImageResize($this->getUploadDirectory() .'/'. $fileName);
            $miniature->resizeToBestFit(200, 200);
            $miniature->save($this->getUploadDirectory() .'/mini/'. $fileName);
            // Images boutiques
            $miniature = new ImageResize($this->getUploadDirectory() .'/'. $fileName);
            $miniature->resizeToBestFit(500, 500);
            $miniature->save($this->getUploadDirectory() .'/boutique/'. $fileName);
        }
    }

    public function uploadAnnonce(array $files, $annonce_id)
    {
        foreach($files as $file)
        {
            $image   = new ImagesAnnonces();
            $annonce = $this->annonceRepository->find($annonce_id);

            // Renommage du fichier + set name en BDD
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $this->slugger->slug($originalFilename);
            $fileName = $annonce_id.'-'.$safeFilename.'-'.uniqid().'.'.$file->guessExtension();
            $image->setTitle($fileName);
            // Set Annonces
            $image->setAnnonce($annonce);
            // Flush
            $this->imagesAnnoncesRepository->add($image, true);

            // Déplacement vers dossier uploads
            $file->move($this->getUploadDirectory(), $fileName);
            unset($file);

            // Resize de l'image (optionnel)
            // Miniature
            $miniature = new ImageResize($this->getUploadDirectory() .'/'. $fileName);
            $miniature->resizeToBestFit(200, 200);
            $miniature->save($this->getUploadDirectory() .'/mini/'. $fileName);
            // Images annonces
            $miniature = new ImageResize($this->getUploadDirectory() .'/'. $fileName);
            $miniature->resizeToBestFit(500, 500);
            $miniature->save($this->getUploadDirectory() .'/annonce/'. $fileName);
        }
    }

    public function uploadHero(UploadedFile $file)
    {
        $image = new ImagesHero();

        // Renommage du fichier + set name en BDD
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
        $image->setTitle($fileName);

        // Flush
        $this->imagesHeroRepository->add($image, true);

        // Déplacement vers dossier uploads
        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            dd($e);
        }

        // Resize de l'image (optionnel)
        // 
        $imgHero = new ImageResize($this->getUploadDirectory() . '/' . $fileName);
        $imgHero->resizeToBestFit(1400, 350);
        $imgHero->save($this->getUploadDirectory() . '/hero/' . $fileName);
    }

    public function uploadProfile(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            dd($e);
        }

        // Resize de l'image (optionnel)
        // Profile
        $avatar = new ImageResize($this->getUploadDirectory() .'/'. $fileName);
        $avatar->resizeToBestFit(100, 100);
        $avatar->save($this->getUploadDirectory() .'/profile/'. $fileName);

        return $fileName;
    }

    public function getUploadDirectory()
    {
        return $this->uploadDirectory;
    }

}