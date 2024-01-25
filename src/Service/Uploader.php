<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Service d'upload d'image
 * 
 * @author carteur007 <saatsafranklin@gmail.com>
 * @link https://github.com/carteur007
 */
class Uploader
{
    /** @var mixed|string repertoire de stockage des images */
    private $targetDirectory;
    /** @var LoggerInterface */
    private $logger;

    public function __construct($targetDirectory, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->targetDirectory = $targetDirectory;
    }
    /**
     * Methode d'upload d'image
     * 
     * @param mixed|UploadedFile $file
     * @return mixed|string $filename Le nom du fichier de sortie de l'image
     */
    public function upload(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            $this->getLogger()->error("Erreur durant l\'upload de fichier | " . $e->getMessage());
        }

        return $fileName;
    }
    /** 
     * @see Psr\Log\LoggerInterface 
     */
    public function getLogger()
    {
        return $this->logger;
    }
    /**
     * @return mixed|string $targetDirectory Le repertoire de stockage des images
     */
    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}