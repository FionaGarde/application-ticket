<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(UserRepository $userRepository): Response
    {
        $connectedUserRoles = $this->getUser()->getRoles();
        if (in_array('ROLE_ADMIN', $connectedUserRoles)) {
            return $this->redirectToRoute('admin_home');
        }

        $users = $userRepository->findAllWithoutSpecifiedId($this->getUser()->getId());

        return $this->render('home/index.html.twig', [
            'users' => $users,
        ]);
    }
}
