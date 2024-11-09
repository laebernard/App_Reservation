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

    #[Route('/service', name: 'app_service')]
    public function index(): Response
    {
        return $this->render('service/index.html.twig', [
            'controller_name' => 'ServiceController',
        ]);
    }
}
