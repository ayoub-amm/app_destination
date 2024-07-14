<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ImageUploader
{
    private $targetDirectory;

    public function __construct(string $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file): string
    {
        $filename = uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $filename);
        } catch (FileException $e) {
            throw new \RuntimeException(sprintf('Could not move the file "%s" to the directory "%s"', $filename, $this->getTargetDirectory()));
        }

        return $filename;
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}
