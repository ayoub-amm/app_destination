<?php

namespace App\Manager;

use App\Entity\Destination;
use App\Repository\DestinationRepository;
use App\Service\ImageUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\EntityManagerInterface;

class DestinationManager
{
    private $destinationRepository;
    private $entityManager;
    private $imageUploader;

    public function __construct(DestinationRepository $destinationRepository, EntityManagerInterface $entityManager, ImageUploader $imageUploader)
    {
        $this->destinationRepository = $destinationRepository;
        $this->entityManager = $entityManager;
        $this->imageUploader = $imageUploader;
    }

    public function getAllDestinations(): array
    {
        return $this->destinationRepository->findAll();
    }

    public function createDestination(Destination $destination, ?UploadedFile $imageFile): void
    {
        if ($imageFile) {
            $imageFileName = $this->imageUploader->upload($imageFile);
            $destination->setImage($imageFileName);
        }
        $this->entityManager->persist($destination);
        $this->entityManager->flush();
    }

    public function updateDestination(Destination $destination, ?UploadedFile $imageFile): void
    {
        if ($imageFile) {
            $imageFileName = $this->imageUploader->upload($imageFile);
            $destination->setImage($imageFileName);
        }
        $this->entityManager->flush();
    }

    public function deleteDestination(Destination $destination): void
    {
        $this->entityManager->remove($destination);
        $this->entityManager->flush();
    }
}
