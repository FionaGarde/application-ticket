<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/list', name: 'user_list')]
    public function list(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);

        // TODO: préparer la route et les données à afficher sur la page d'accueil de l'admin
        // TODO: préparer la route et les données à afficher pour les stats de l'admin
        // TODO: préparer la route pour la page qui va lister tous les message à modérer pour l'admin
        // TODO: page qui liste les élèves de la plateforme sauf l'élève connecté pour qu'ils puissent envoyer un message
        // TODO: permettre de présélectionner un user depuis la liste des élèves
        // TODO: afficher sur la home le message s'il a déjà envoyé un message et s'il a été validé ou pas, (si non validé possibilité de renvoyer vers la page de modification de message
    }

    #[Route('/', name: 'user_index')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
