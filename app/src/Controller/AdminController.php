<?php

namespace App\Controller;

use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/validated/{id}', name: 'validate')]
    public function validateMessage(EntityManagerInterface $entityManager, Message $message): Response
    {
        $message->setState(2);
        $entityManager->flush();
        return $this->redirectToRoute('admin_home');
    }

    #[Route('/refused/{id}', name: 'refuse')]
    public function refuseMessage(EntityManagerInterface $entityManager, Message $message): Response
    {
        $message->setState(3);
        $entityManager->flush();
        return $this->redirectToRoute('admin_home');
    }
    #[Route('/', name: 'home')]
    public function userInformations( UserRepository $userRepository, Mesage $message): Response
    {
        $users = $userRepository->findAll();
    
        return $this->render('admin/index.html.twig', [
            'users' => $users,
        ]);
    }
    #[Route('/stats', name: 'stats')]
    public function stats(UserRepository $userRepository, MessageRepository $messageRepository): Response
    {
        $users = $userRepository->findAll();
        $usersCount = count($users);
        $messagesToModerateCount = count($messagesRepository->findBy(array('state' => '1')));
        $messagesValidatedCount = count($messagesRepository->findBy(array('state' => '2')));
        $messagesRefusedCount = count($messagesRepository->findBy(array('state' => '3')));
        $sendedCount = count($messagesRepository->findAll());
        

        return $this->render('admin/stats.html.twig', [
            'messagesToModerateCount' => $messagesToModerateCount,
            'messagesValidatedCount' => $messagesValidatedCount,
            'messagesRefusedCount' => $messagesRefusedCount, 
            'sendedCount' => $sendedCount,
        ]);
    }
    

}
