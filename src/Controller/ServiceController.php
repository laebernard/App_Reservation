<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Service;
use App\Repository\ServiceRepository;

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
    public function menuService(ServiceRepository $serviceRepository, string $service): Response
    {
        $serviceSelectionne = $serviceRepository->findOneBy(['nom' => $service]);
        $templatePath = sprintf('service/%s.html.twig', $service);
        $services = $serviceRepository->findAll();
        return $this->render($templatePath, [
            'services' => $services,
            'service' => $serviceSelectionne
        ]);
    }
}
