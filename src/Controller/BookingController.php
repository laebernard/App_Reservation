<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Booking;
use App\Form\BookingType;
use App\Repository\ServiceRepository;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class BookingController extends AbstractController
{
    #[Route('/booking', name: 'app_booking')]
    public function index(): Response
    {
        return $this->render('booking/index.html.twig', [
            'controller_name' => 'BookingController',
        ]);
    }

    #[Route('/booking/create', name: 'create_booking')]
    public function CreateBooking(ServiceRepository $repository, EntityManagerInterface $em, Request $request): Response
    {
        $services = $repository->findAll();
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($booking);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }
        return $this->render('booking/create.html.twig', [
            'form' => $form->createView(),
            'services' => $services
        ]);
    }

    #[Route('/booking/{id}/edit', name: 'edit_booking')]
    public function edit(ServiceRepository $serviceRepository,BookingRepository $bookingRepository, EntityManagerInterface $em, int $id, Request $request): Response
    {
        $services = $serviceRepository->findAll();
        $booking = $bookingRepository->find($id);
        
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($booking);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('booking/edit.html.twig', [
            'form' => $form->createView(), 
            'services' => $services
        ]);
    }


    #[Route('/booking/{id}/delete', name: 'delete_booking', requirements: ['id' => '\d+'])]
    public function delete(ServiceRepository $repository, int $id, EntityManagerInterface $em): Response
    {
        $booking = $em->getRepository(Booking::class)->find($id);
        $services = $repository->findAll();

        if ($booking) {
            $em->remove($booking);
            $em->flush();

            // Redirection vers la liste des reservations après suppression
            return $this->redirectToRoute('app_home');
        }

        // Affichage d'un message d'erreur si la reservation n'est pas trouvé
        return $this->render('booking/delete.html.twig', [
            'error' => 'Reservation non trouvé',
            'services' => $services
        ]); 
    }
}
