<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/users')]
class AdminUserController extends AbstractController
{

    #[Route('/', name: 'admin_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/create', name: 'admin_user_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $roles = $this->getAvailableRoles();

        if ($request->isMethod('POST')) {
            $data = $request->request->all();

            if (empty($data['name']) || empty($data['email']) || empty($data['role']) || empty($data['password'])) {
                $this->addFlash('error', 'Tous les champs sont requis.');
                return $this->redirectToRoute('admin_user_create');
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

            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('admin/user/create.html.twig', [
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
            'Gestionnaire de fournisseurs' => 'ROLE_SUPPLIER_MANAGER',
            "Gestionnaire d'agences" => 'ROLE_AGENCY_MANAGER',
        ];
    }

    #[Route('/users/store', name: 'admin_user_store', methods: ['POST'])]
    public function store(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = $request->request->all();

        if (empty($data['name']) || empty($data['email']) || empty($data['role']) || empty($data['password'])) {
            $this->addFlash('error', 'Tous les champs sont requis.');
            return $this->redirectToRoute('user_create');
        }

        $user = new User();
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setRoles([$data['role']]);
        $user->setPassword(password_hash($data['password'], PASSWORD_BCRYPT));
        $user->setCreatedAt(new \DateTimeImmutable());

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('admin_user_index');
    }


    #[Route('/{id<\d+>}', name: 'admin_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }


    // #[Route('/profile', name: 'user_profile', methods: ['GET'])]
    // public function profile(): Response
    // {
    //     $user = $this->getUser();
    //     return $this->render('admin/user/profile.html.twig', [
    //         'user' => $user,
    //     ]);
    // }


    // #[Route('/profile/edit', name: 'user_profile_edit', methods: ['GET', 'POST'])]
    // public function editProfile(
    //     Request $request,
    //     EntityManagerInterface $entityManager,
    //     UserPasswordHasherInterface $passwordHasher
    // ): Response {
    //     /** @var \App\Entity\User */
    //     $user = $this->getUser();

    //     if (!$user) {
    //         throw $this->createAccessDeniedException('Vous devez être connecté pour modifier votre profil.');
    //     }

    //     $form = $this->createForm(UserFormType::class, $user);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         if ($form->get('plainPassword')->getData()) {
    //             $user->setPassword(
    //                 $passwordHasher->hashPassword($user, $form->get('plainPassword')->getData())
    //             );
    //         }

    //         $user->setCreatedAt(new \DateTimeImmutable());
    //         $entityManager->flush();

    //         $this->addFlash('success', 'Votre profil a été mis à jour.');

    //         return $this->redirectToRoute('user_profile');
    //     }

    //     return $this->render('admin/user/edit_profile.html.twig', [
    //         'form' => $form->createView(),
    //         'user' => $user,
    //     ]);
    // }


    #[Route('/{id}/edit', name: 'admin_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $roles = $this->getAvailableRoles();

        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('plainPassword')->getData()) {
                $user->setPassword(
                    $passwordHasher->hashPassword($user, $form->get('plainPassword')->getData())
                );
            }

            if ($user->getEmailVerifiedAt() === null) {
                $user->setEmailVerifiedAt(new \DateTimeImmutable());
            }

            $entityManager->flush();


            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    #[Route('/users/{id}/update', name: 'admin_user_update', methods: ['POST', 'PUT'])]
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

        return $this->redirectToRoute('admin_user_index');
    }

    #[Route('/{id}/delete', name: 'admin_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_user_index');
    }
}
