<?php

namespace App\Controller;

use App\Entity\Message;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('admin/index.html.twig', [
            'users' => $users,
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

    #[Route('/stats', name: 'stats')]
    public function stats(UserRepository $userRepository, MessageRepository $messageRepository): Response
    {
        $users = $userRepository->findAll();
        $usersCount = count($users);
        $messagesToModerateCount = count($messageRepository->findBy(array('state' => '1')));
        $messagesValidatedCount = count($messageRepository->findBy(array('state' => '2')));
        $messagesRefusedCount = count($messageRepository->findBy(array('state' => '3')));
        $sendedCount = count($messageRepository->findAll());
        

        return $this->render('admin/stats.html.twig', [
            'messagesToModerateCount' => $messagesToModerateCount,
            'messagesValidatedCount' => $messagesValidatedCount,
            'messagesRefusedCount' => $messagesRefusedCount, 
            'sendedCount' => $sendedCount,
        ]);
    }
    

}
