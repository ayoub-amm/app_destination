<?php

namespace App\Controller;

use App\Entity\Destination;
use App\Manager\DestinationManager;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $destinationManager;
    private $logger;

    public function __construct(DestinationManager $destinationManager, LoggerInterface $logger)
    {
        $this->destinationManager = $destinationManager;
        $this->logger = $logger;
    }

   
    #[Route('/', name: 'home', methods: ['GET'])]
     public function index(): Response
     {
         try {
             $destinations = $this->destinationManager->getAllDestinations();
             return $this->render('home/index.html.twig', [
                 'destinations' => $destinations,
             ]);
         } catch (Exception $e) {
             $this->logger->error('Error displaying destination index: ' . $e->getMessage());
             $this->addFlash('error', 'Error fetching destinations.');
             return $this->redirectToRoute('home');
         }
     }

     #[Route('/destination/{id}', name: 'destination_show', methods: ['GET'])]
     public function show(Destination $destination): Response
     {
         try {
             if (!$destination) {
                 throw $this->createNotFoundException('The destination does not exist');
             }
             return $this->render('home/show.html.twig', [
                 'destination' => $destination,
             ]);
         } catch (Exception $e) {
             $this->logger->error('Error displaying destination: ' . $e->getMessage());
             $this->addFlash('error', 'Error fetching destination details.');
             return $this->redirectToRoute('home');
         }
     }
}
