<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Service;
use App\Entity\Booking;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;

class ServiceController extends AbstractController
{

    #[Route('/base', name: 'app_base')]
    public function base(ServiceRepository $repository):Response
    {
        $services = $repository->findAll();
        return $this->render('base.html.twig', [
            'services' => $services
        ]);
    }

    #[Route('/{service}', name: 'show_service')]
    public function menuService(EntityManagerInterface $em, string $service): Response
    {
        $serviceRepository = $em->getRepository(Service::class);
        $serviceSelectionne = $serviceRepository->findOneBy(['nom' => $service]);
        $bookings = $em->getRepository(Booking::class)->findBy(["serviceId" => $serviceSelectionne->getId()]);
        $templatePath = sprintf('service/%s.html.twig', $service);
        $services = $serviceRepository->findAll();
        return $this->render($templatePath, [
            'services' => $services,
            'service' => $serviceSelectionne,
            'bookings' => $bookings
        ]);
    }
}
