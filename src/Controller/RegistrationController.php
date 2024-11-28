<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer le mot de passe brut (plain password)
            $plainPassword = $form->get('plainPassword')->getData();

            // Assurez-vous que la valeur n'est pas null
            if ($plainPassword === null || $plainPassword === '') {
                $this->addFlash('danger', 'Le mot de passe est requis.');
                return $this->redirectToRoute('http://127.0.0.1:8000/main');
            }

            // Hasher le mot de passe
            $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);

            // Définir les rôles par défaut
            $user->setRoles(['ROLE_USER']);

            // Sauvegarder l'utilisateur
            $entityManager->persist($user);
            $entityManager->flush();
            dump($user);
            $formView = $form->createView();
            $this->addFlash('success', 'Inscription réussie. Vous pouvez maintenant vous connecter.');

            return $this->redirectToRoute('http://127.0.0.1:8000/main');
        }

        return $this->render('registration/index.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
