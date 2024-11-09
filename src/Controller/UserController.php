<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        $services = $repository->findAll();
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'services' => $services
        ]);
    }

    #[Route('/user/create', name: 'create_user')]
    public function CreateUser(ServiceRepository $repository, EntityManagerInterface $em, Request $request): Response
    {
        $services = $repository->findAll();
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }
        return $this->render('user/create.html.twig', [
            'form' => $form->createView(),
            'services' => $services
        ]);
    }
}
