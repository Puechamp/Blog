<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        $user = $this->getUser(); // Obtenir l'utilisateur connecté
        if (!$user) {
            return $this->redirectToRoute('app_login'); // Redirige si non connecté
        }

        return $this->render('profile/profile.html.twig', [
            'user' => $user,
        ]);
    }
}
