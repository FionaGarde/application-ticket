<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/message', name: 'message_')]
class MessageController extends AbstractController
{
    #[Route('/show', name: 'show')]
    public function show(ManagerRegistry $managerRegistry, Request $request): Response
    {
        $user = $this->getUser();
        $sendedMessage = $user->getSendedMessage();

        if (!$sendedMessage) {
            $this->addFlash('warning', "Tu n'a envoyé aucun message pour le moment");

            return $this->redirectToRoute('message_new');
        }

        return $this->render('message/show.html.twig', [
            'message' => $sendedMessage,
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(EntityManagerInterface $entityManager, Request $request): Response
    {
        $message = new Message();

        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setSender($this->getUser());
            $message->setState(1);

            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('message_show');
        }

        return $this->render('message/new.html.twig', [
            'newMessageForm' => $form->createView(),
        ]);
    }
}
