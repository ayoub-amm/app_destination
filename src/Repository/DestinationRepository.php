<?php

namespace App\Repository;

use App\Entity\Destination;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DestinationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Destination::class);
    }

    public function save(Destination $destination): void
    {
        $this->em->persist($destination);
        $this->em->flush();
    }

    public function remove(Destination $destination): void
    {
        $this->em->remove($destination);
        $this->em->flush();
    }
}
