<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/users')]
class UserController extends AbstractController
{
    #[Route('/', name: 'user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/create', name: 'user_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $roles = $this->getAvailableRoles();

        if ($request->isMethod('POST')) {
            $data = $request->request->all();

            if (empty($data['name']) || empty($data['email']) || empty($data['role']) || empty($data['password'])) {
                $this->addFlash('error', 'Tous les champs sont requis.');
                return $this->redirectToRoute('user_create');
            }

            $user = new User();
            $user->setName($data['name']);
            $user->setEmail($data['email']);
            $user->setRoles([$data['role']]);
            $user->setPassword($passwordHasher->hashPassword($user, $data['password']));
            $user->setCreatedAt(new \DateTimeImmutable());
            $user->setEmailVerifiedAt(new \DateTimeImmutable()); 

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/create.html.twig', [
            'roles' => $roles
        ]);
    }


    private function getAvailableRoles(): array
    {
        return [
            'Administrateur' => 'ROLE_ADMIN',
            'Utilisateur VIP' => 'ROLE_USER_VIP',
            'Utilisateur' => 'ROLE_USER',
            'Chef d\'agence' => 'ROLE_AGENCY_HEAD',
            'Gestionnaire de commandes' => 'ROLE_ORDER_MANAGER',
            'Fournisseur' => 'ROLE_SUPPLIER'
        ];
    }

    #[Route('/users/store', name: 'user_store', methods: ['POST'])]
    public function store(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = $request->request->all();

        // Validation simple (ajoutez des règles plus robustes si nécessaire)
        if (empty($data['name']) || empty($data['email']) || empty($data['role']) || empty($data['password'])) {
            $this->addFlash('error', 'Tous les champs sont requis.');
            return $this->redirectToRoute('user_create');
        }

        $user = new User();
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setRoles([$data['role']]); // Stocker le rôle
        $user->setPassword(password_hash($data['password'], PASSWORD_BCRYPT));
        $user->setCreatedAt(new \DateTimeImmutable());

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('user_index');
    }


    #[Route('/{id}', name: 'user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        // Récupération des rôles disponibles
        $roles = $this->getAvailableRoles();

        // Création du formulaire avec les données existantes de l'utilisateur
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Mise à jour du mot de passe s'il est fourni
            if ($form->get('plainPassword')->getData()) {
                $user->setPassword(
                    $passwordHasher->hashPassword($user, $form->get('plainPassword')->getData())
                );
            }

            // Assurer que email_verified_at est défini
            if ($user->getEmailVerifiedAt() === null) {
                $user->setEmailVerifiedAt(new \DateTimeImmutable());
            }

            // Mise à jour du champ updated_at
            $user->setUpdatedAt(new \DateTimeImmutable());

            $entityManager->flush();


            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    #[Route('/users/{id}/update', name: 'user_update', methods: ['POST', 'PUT'])]
    public function update(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $data = $request->request->all();

        if (empty($data['name']) || empty($data['email']) || empty($data['role'])) {
            $this->addFlash('error', 'Tous les champs sont requis.');
            return $this->redirectToRoute('user_edit', ['id' => $user->getId()]);
        }

        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setRoles([$data['role']]);

        if (!empty($data['password']) && $data['password'] === $data['password_confirmation']) {
            $user->setPassword(password_hash($data['password'], PASSWORD_BCRYPT));
        }

        $entityManager->flush();

        return $this->redirectToRoute('user_index');
    }

    #[Route('/{id}/delete', name: 'user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();

        }

        return $this->redirectToRoute('user_index');
    }
}
