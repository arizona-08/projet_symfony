<?php

namespace App\Controller;

use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/profile')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'profile', methods: ['GET'])]
    public function profile(): Response
    {
        $user = $this->getUser();
        return $this->render('profile/profile.html.twig', [
            'user' => $user,
        ]);
    }


    #[Route('/edit', name: 'profile_edit', methods: ['GET', 'POST'])]
    public function editProfile(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour modifier votre profil.');
        }

        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('plainPassword')->getData()) {
                $user->setPassword(
                    $passwordHasher->hashPassword($user, $form->get('plainPassword')->getData())
                );
            }

            $user->setCreatedAt(new \DateTimeImmutable());
            $entityManager->flush();

            $this->addFlash('success', 'Votre profil a été mis à jour.');

            return $this->redirectToRoute('profile');
        }

        return $this->render('profile/edit_profile.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}
