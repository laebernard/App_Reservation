<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use App\Entity\Service;
use App\Entity\Booking;
use App\Form\BookingType;
use App\Repository\ServiceRepository;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;



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
    public function CreateBooking(EntityManagerInterface $em, Request $request): Response
    {
        $services = $em->getRepository(Service::class)->findAll();
        $bookingRepository = $em->getRepository(Booking::class);
        $controleDatesHeures = $bookingRepository->findDaysOk();
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);
      
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $dateActuelle = new \DateTime('now');
            $heureActuelle = $dateActuelle->format('H:i');
 

            
            if($booking->getDate()->format('Y-m-d') > $dateActuelle->format('Y-m-d')){
                $form->addError(new FormError("La date selectionnée est une date dans le passé."));

            }

            $isDuplicate = false;
    
            foreach ($controleDatesHeures as $DateHeure) {
                if (
                    $DateHeure['date'] == $booking->getDate()->format('Y-m-d') && 
                    $DateHeure['heure'] == $booking->getHeure()->format('H:i:s')
                ) {
                    $isDuplicate = true;
                    break;
                }
            }

            $userData = $booking->getUser();
            $userRepository = $em->getRepository(User::class);
            
            $user = $userRepository->findOneBy(['email' => $userData->getEmail()]);

            if (!$user) {
                $user = new user();
                $user->setNom($userData->getNom());
                $user->setPrenom($userData->getPrenom());
                $user->setEmail($userData->getEmail());
                $em->persist($user);
            }

            $booking->setUser($user);

            if ($isDuplicate) {
                $form->addError(new FormError("Un rendez-vous est déjà prévu à cette date et heure."));
            } else {

                $em->persist($booking);
                $em->flush();

                return $this->redirectToRoute('app_home');


                
            }

        }
        return $this->render('booking/create.html.twig', [
            'form' => $form->createView(),
            'services' => $services
        ]);
    }

    #[Route('/booking/{id}/edit', name: 'edit_booking')]
    public function edit(EntityManagerInterface $em, int $id, Request $request): Response
    {
        $services = $em->getRepository(Service::class)->findAll();
        $bookingRepository = $em->getRepository(Booking::class);
        $controleDatesHeures = $bookingRepository->findDaysOk();
        $booking = $bookingRepository->find($id);
        
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dateActuelle = new \DateTime('now');
            $heureActuelle = $dateActuelle->format('H:i');
 
            if($booking->getDate()->format('Y-m-d') > $dateActuelle->format('Y-m-d')){
                $form->addError(new FormError("La date selectionnée est une date dans le passé."));

            }

            $isDuplicate = false;
    
            foreach ($controleDatesHeures as $DateHeure) {
                if (
                    $DateHeure['date'] == $booking->getDate()->format('Y-m-d') && 
                    $DateHeure['heure'] == $booking->getHeure()->format('H:i:s')
                ) {
                    $isDuplicate = true;
                    break;
                }
            }

            $userData = $booking->getUser();
            $userRepository = $em->getRepository(User::class);
            
            $user = $userRepository->findOneBy(['email' => $userData->getEmail()]);

            if (!$user) {
                $user = new user();
                $user->setNom($userData->getNom());
                $user->setPrenom($userData->getPrenom());
                $user->setEmail($userData->getEmail());
                $em->persist($user);
            }

            $booking->setUser($user);
            dump($isDuplicate);

            if ($isDuplicate) {
                $form->addError(new FormError("Un rendez-vous est déjà prévu à cette date et heure."));
            } else {
                $em->persist($booking);
                $em->flush();

                return $this->redirectToRoute('app_home');

            }

        }

        return $this->render('booking/edit.html.twig', [
            'form' => $form->createView(), 
            'services' => $services
        ]);
    }


    #[Route('/booking/{id}/delete', name: 'delete_booking', requirements: ['id' => '\d+'])]
    public function delete(int $id, EntityManagerInterface $em): Response
    {
        $booking = $em->getRepository(Booking::class)->find($id);

        $services = $em->getRepository(Service::class)->findAll();

        if ($booking) {
            $em->remove($booking);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('booking/delete.html.twig', [
            'error' => 'Reservation non trouvé',
            'services' => $services
        ]); 
    }
}
