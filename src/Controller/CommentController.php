<?php

namespace App\Controller;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/comments', name: 'comments_')]
class CommentController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $comments = $entityManager->getRepository(Comment::class)->findAll();

        return $this->render('comments/index.html.twig', [
            'comments' => $comments,
        ]);
    }

    #[Route('/approve/{id}', name: 'approve')]
    public function approve(Comment $comment, EntityManagerInterface $entityManager): Response
    {
        $entityManager->flush();

        $this->addFlash('success', 'Commentaire approuvé avec succès.');
        return $this->redirectToRoute('comments_index');
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Comment $comment, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($comment);
        $entityManager->flush();

        $this->addFlash('danger', 'Commentaire supprimé avec succès.');
        return $this->redirectToRoute('comments_index');
    }
}
