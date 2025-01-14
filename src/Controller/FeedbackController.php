<?php

namespace App\Controller;

use App\Entity\Feedback;
use App\Entity\Location;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FeedbackController extends AbstractController
{
    #[Route('/feedback', name: 'app_feedback')]
    public function index(): Response
    {
        return $this->render('feedback/index.html.twig', [
            'controller_name' => 'FeedbackController',
        ]);
    }

    #[Route('/feedback/submit/{id}/{rating}', name: 'feedback_submit', methods: ['GET'])]
    public function submitFeedback(int $id, int $rating, EntityManagerInterface $entityManager): Response
    {
        if ($rating < 1 || $rating > 5) {
            throw $this->createNotFoundException('Note invalide.');
        }

        $location = $entityManager->getRepository(Location::class)->find($id);
        if (!$location) {
            throw $this->createNotFoundException('Commande non trouvée.');
        }

        $existingFeedback = $entityManager->getRepository(Feedback::class)
            ->findOneBy(['location' => $location]);

        if ($existingFeedback) {
            $this->addFlash('error', 'Vous avez déjà laissé une note pour cette commande.');
            return $this->redirectToRoute('app_my_locations');
        }

        $feedback = new Feedback();
        $feedback->setLocation($location);
        $feedback->setRating($rating);
        $feedback->setClient($this->getUser());

        $entityManager->persist($feedback);
        $entityManager->flush();

        $this->addFlash('success', 'Merci pour votre avis !');
        return $this->redirectToRoute('app_my_locations');
    }

    #[Route('/feedback/comment/{id}', name: 'feedback_submit_comment', methods: ['POST'])]
    public function submitComment(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $location = $entityManager->getRepository(Location::class)->find($id);

        if (!$location) {
            throw $this->createNotFoundException('Commande non trouvée.');
        }

        $existingFeedback = $entityManager->getRepository(Feedback::class)
            ->findOneBy(['location' => $location]);

        if (!$existingFeedback) {
            $this->addFlash('error', 'Veuillez noter cette commande avant de laisser un commentaire.');
            return $this->redirectToRoute('app_my_locations');
        }

        $comment = $request->request->get('comment');
        if (empty($comment)) {
            $this->addFlash('error', 'Le commentaire ne peut pas être vide.');
            return $this->redirectToRoute('app_my_locations');
        }

        $existingFeedback->setComment($comment);
        $entityManager->flush();

        $this->addFlash('success', 'Votre commentaire a été enregistré.');
        return $this->redirectToRoute('app_my_locations');
    }
}
