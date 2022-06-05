<?php

namespace App\Service;

use App\Entity\Images;
use App\Entity\ImagesBoutique;
use App\Repository\BoutiqueRepository;
use App\Repository\ImagesBoutiqueRepository;
use Gumlet\ImageResize;
use Symfony\Component\String\Slugger\SluggerInterface;

class UploadImage
{
    private $uploadDirectory;
    private $slugger;
    private $imagesBoutiqueRepository;
    private $boutiqueRepository;

    public function __construct($uploadDirectory, SluggerInterface $slugger, ImagesBoutiqueRepository $imagesBoutiqueRepository, BoutiqueRepository $boutiqueRepository)
    {
        $this->uploadDirectory = $uploadDirectory;
        $this->slugger = $slugger;
        $this->imagesBoutiqueRepository = $imagesBoutiqueRepository;
        $this->boutiqueRepository = $boutiqueRepository;
    }

    public function upload(array $files, $boutique_id)
    {
        foreach($files as $file)
        {
            $image = new ImagesBoutique();
            $boutique = $this->boutiqueRepository->find($boutique_id);

            // Renommage du fichier + set name en BDD
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $this->slugger->slug($originalFilename);
            $fileName = $boutique->getId().'-'.$safeFilename.'-'.uniqid().'.'.$file->guessExtension();
            $image->setTitle($fileName);
            // Set Animation via l'ID récupéré en paramètre
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