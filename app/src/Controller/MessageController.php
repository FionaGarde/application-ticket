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
        $user = $this->getUser();
        $sendedMessage = $user->getSendedMessage();

        if ($sendedMessage) {
            $this->addFlash('danger', "Tu as déjà envoyé un message");

            return $this->redirectToRoute('message_show');
        }

        $message = new Message();

        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setSender($this->getUser());
            $message->setState(1);

            $entityManager->persist($message);
            $entityManager->flush();

            $this->addFlash('success', "Message correctement envoyé !");

            return $this->redirectToRoute('message_show');
        }

        return $this->render('message/new.html.twig', [
            'newMessageForm' => $form->createView(),
        ]);
    }

    #[Route('/edit', name: 'edit')]
    public function edit(EntityManagerInterface $entityManager, Request $request): Response
    {
        $user = $this->getUser();
        $sendedMessage = $user->getSendedMessage();
        $stateMessage = $sendedMessage->getState();

        //si le message existe ET que state = 3
        if ($sendedMessage) {
            switch ($stateMessage) {
                case 1:
                    $this->addFlash('warning', "Ton message est en attente de vérification");
                    return $this->redirectToRoute("home");
                case 2:
                    $this->addFlash('warning', "Ton message a été validé, tu ne peux plus le modifier :)");
                    return $this->redirectToRoute("home");
                case 3:
                    //on sort du switch
                    break;
            }

            $form = $this->createForm(MessageType::class, $sendedMessage);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $sendedMessage->setUpdatedAt(new \DateTime());
                $sendedMessage->setState(1);
                $entityManager->flush();

                $this->addFlash('success', "Message correctement modifié =] !");
                return $this->redirectToRoute('message_show');
            }

            // TODO : faire en sorte que le champ receiver

            return $this->render('message/edit.html.twig', [
                'messageForm' => $form->createView(),
            ]);
        }
    }

}
