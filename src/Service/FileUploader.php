<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    public function __construct(
        private SluggerInterface $slugger,
        private string $uploadDirectory
    )
    {
    }

    public function upload(UploadedFile $uploadedFile)
    {
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName         = $this->slugger->slug($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();

        try {
            $uploadedFile->move($this->uploadDirectory, $fileName);
        } catch (FileException $e) {
            return false;
        }

        return $fileName;
    }
}