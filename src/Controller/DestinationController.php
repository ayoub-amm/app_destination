<?php

namespace App\Controller;

use App\Entity\Destination;
use App\Form\DestinationFormType;
use App\Manager\DestinationManager;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Exception;

#[Route('/destination')]
#[IsGranted('ROLE_ADMIN')]
class DestinationController extends AbstractController
{
    private $destinationManager;
    private $logger;

    public function __construct(DestinationManager $destinationManager, LoggerInterface $logger)
    {
        $this->destinationManager = $destinationManager;
        $this->logger = $logger;
    }

    #[Route('/new', name: 'destination_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $destination = new Destination();
        $form = $this->createForm(DestinationFormType::class, $destination);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $imageFile = $form->get('image')->getData();
                $this->destinationManager->createDestination($destination, $imageFile);

                return $this->redirectToRoute('home');
            } catch (Exception $e) {
                $this->logger->error('Error creating new destination: ' . $e->getMessage());
                $this->addFlash('error', 'Error creating destination.');
            }
        }

        return $this->render('destination/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

   

    #[Route('/{id}/edit', name: 'destination_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Destination $destination): Response
    {
        try {
            if (!$destination) {
                throw $this->createNotFoundException('The destination does not exist');
            }

            $form = $this->createForm(DestinationFormType::class, $destination);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                try {
                    $imageFile = $form->get('image')->getData();
                    $this->destinationManager->updateDestination($destination, $imageFile);

                    return $this->redirectToRoute('home');
                } catch (Exception $e) {
                    $this->logger->error('Error updating destination: ' . $e->getMessage());
                    $this->addFlash('error', 'Error updating destination.');
                }
            }

            return $this->render('destination/edit.html.twig', [
                'form' => $form->createView(),
                'destination' => $destination,
            ]);
        } catch (Exception $e) {
            $this->logger->error('Error fetching destination for edit: ' . $e->getMessage());
            $this->addFlash('error', 'Error fetching destination.');
            return $this->redirectToRoute('home');
        }
    }

    #[Route('/{id}', name: 'destination_delete', methods: ['POST'])]
    public function delete(Request $request, Destination $destination): Response
    {
        try {
            if (!$destination) {
                throw $this->createNotFoundException('The destination does not exist');
            }

            if ($this->isCsrfTokenValid('delete'.$destination->getId(), $request->request->get('_token'))) {
                $this->destinationManager->deleteDestination($destination);
                $this->addFlash('success', 'Destination deleted successfully.');
            }

            return $this->redirectToRoute('home');
        } catch (Exception $e) {
            $this->logger->error('Error deleting destination: ' . $e->getMessage());
            $this->addFlash('error', 'Error deleting destination.');
            return $this->redirectToRoute('home');
        }
    }
}
