<?php

namespace App\Controller;

use App\Entity\Agency;
use App\Entity\User;
use App\Repository\AgencyRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AgencyController extends AbstractController
{
    #[Route('/agencies', name: 'agency_index', methods: ['GET'])]
    public function index(AgencyRepository $agencyRepository): Response
    {
        $user = $this->getUser();

        // Commenté pour ignorer les rôles
        // $agencies = $this->isGranted('ROLE_AGENCY_HEAD')
        //     ? $agencyRepository->findBy(['user' => $user])
        //     : $agencyRepository->findAll();

        $agencies = $agencyRepository->findAll();

        return $this->render('agency/index.html.twig', [
            'agencies' => $agencies,
        ]);
    }

    #[Route('/agencies/create', name: 'agency_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        if ($request->isMethod('POST')) {
            $data = $request->request->all();

            if (empty($data['label']) || empty($data['address']) || empty($data['city']) || empty($data['zip_code']) || empty($data['user_id'])) {
                $this->addFlash('error', 'Tous les champs requis doivent être remplis.');
                return $this->redirectToRoute('agency_create');
            }

            $existingAgency = $entityManager->getRepository(Agency::class)->findOneBy(['user' => $data['user_id']]);
            if ($existingAgency) {
                $this->addFlash('error', 'Cet utilisateur est déjà associé à une agence.');
                return $this->redirectToRoute('agency_index');
            }

            $user = $userRepository->find($data['user_id']);
            if (!$user) {
                $this->addFlash('error', 'Utilisateur non trouvé.');
                return $this->redirectToRoute('agency_create');
            }

            $agency = new Agency();
            $agency->setLabel($data['label']);
            $agency->setAddress($data['address']);
            $agency->setCity($data['city']);
            $agency->setZipCode($data['zip_code']);
            $agency->setUser($user);

            $entityManager->persist($agency);
            $entityManager->flush();

            $this->addFlash('success', 'Agence créée avec succès.');
            return $this->redirectToRoute('agency_index');
        }

        $users = $userRepository->findAll();

        return $this->render('agency/create.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/agencies/store', name: 'agency_store', methods: ['POST'])]
    public function store(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $data = $request->request->all();

        if (empty($data['label']) || empty($data['address']) || empty($data['city']) || empty($data['zip_code']) || empty($data['user_id'])) {
            $this->addFlash('error', 'Tous les champs requis doivent être remplis.');
            return $this->redirectToRoute('agency_create');
        }

        $existingAgency = $entityManager->getRepository(Agency::class)->findOneBy(['user' => $data['user_id']]);
        if ($existingAgency) {
            $this->addFlash('error', 'Cet utilisateur est déjà associé à une agence.');
            return $this->redirectToRoute('agency_index');
        }

        $user = $userRepository->find($data['user_id']);
        if (!$user) {
            $this->addFlash('error', 'Utilisateur non trouvé.');
            return $this->redirectToRoute('agency_create');
        }

        $agency = new Agency();
        $agency->setLabel($data['label']);
        $agency->setAddress($data['address']);
        $agency->setCity($data['city']);
        $agency->setZipCode($data['zip_code']);
        $agency->setUser($user);

        $entityManager->persist($agency);
        $entityManager->flush();

        $this->addFlash('success', 'Agence créée avec succès.');
        return $this->redirectToRoute('agency_index');
    }

    #[Route('/agencies/{id}', name: 'agency_show', methods: ['GET'])]
    public function show(Agency $agency): Response
    {
        return $this->render('agency/show.html.twig', [
            'agency' => $agency,
        ]);
    }

    #[Route('/agencies/{id}/edit', name: 'agency_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Agency $agency, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        if ($request->isMethod('POST')) {
            $data = $request->request->all();

            $agency->setLabel($data['label']);
            $agency->setAddress($data['address']);
            $agency->setCity($data['city']);
            $agency->setZipCode($data['zip_code']);

            if ($agency->getUser()->getId() != $data['user_id']) {
                $existingAgency = $entityManager->getRepository(Agency::class)->findOneBy(['user' => $data['user_id']]);
                if ($existingAgency) {
                    $this->addFlash('error', 'Cet utilisateur est déjà associé à une agence.');
                    return $this->redirectToRoute('agency_index');
                }

                $newUser = $userRepository->find($data['user_id']);
                $agency->setUser($newUser);
            }

            $entityManager->flush();
            $this->addFlash('success', 'Agence mise à jour avec succès.');
            return $this->redirectToRoute('agency_index');
        }

        $users = $userRepository->findAll();

        return $this->render('agency/edit.html.twig', [
            'agency' => $agency,
            'users' => $users,
        ]);
    }

    #[Route('/agencies/{id}/update', name: 'agency_update', methods: ['POST', 'PUT'])]
    public function update(Request $request, Agency $agency, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $data = $request->request->all();

        if (empty($data['label']) || empty($data['address']) || empty($data['city']) || empty($data['zip_code']) || empty($data['user_id'])) {
            $this->addFlash('error', 'Tous les champs requis doivent être remplis.');
            return $this->redirectToRoute('agency_edit', ['id' => $agency->getId()]);
        }

        $agency->setLabel($data['label']);
        $agency->setAddress($data['address']);
        $agency->setCity($data['city']);
        $agency->setZipCode($data['zip_code']);

        if ($agency->getUser()->getId() !== (int) $data['user_id']) {
            $existingAgency = $entityManager->getRepository(Agency::class)->findOneBy(['user' => $data['user_id']]);
            if ($existingAgency) {
                $this->addFlash('error', 'Cet utilisateur est déjà associé à une agence.');
                return $this->redirectToRoute('agency_edit', ['id' => $agency->getId()]);
            }

            $newUser = $userRepository->find((int) $data['user_id']);
            if (!$newUser) {
                $this->addFlash('error', 'Utilisateur non trouvé.');
                return $this->redirectToRoute('agency_edit', ['id' => $agency->getId()]);
            }

            $agency->setUser($newUser);
        }

        $entityManager->flush();

        $this->addFlash('success', 'Agence mise à jour avec succès.');
        return $this->redirectToRoute('agency_index');
    }


    #[Route('/agencies/{id}/delete', name: 'agency_delete', methods: ['POST'])]
    public function delete(Agency $agency, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($agency);
        $entityManager->flush();

        $this->addFlash('success', 'Agence supprimée avec succès.');
        return $this->redirectToRoute('agency_index');
    }
}
