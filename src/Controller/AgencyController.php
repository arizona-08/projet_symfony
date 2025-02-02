<?php

namespace App\Controller;

use App\Entity\Agency;
use App\Entity\User;
use App\Repository\AgencyRepository;
use App\Repository\UserRepository;
use App\Utils\UsersGetter;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AgencyController extends AbstractController
{
    private UsersGetter $userGetter;
    public function __construct(UserRepository $userRepository)
    {
        $this->userGetter = new UsersGetter($userRepository);
    }

    #[Route('/agencies', name: 'agency_index', methods: ['GET'])]
    public function index(
        AgencyRepository $agencyRepository,
        Request $request,
        PaginatorInterface $paginator
    ): Response {
        /** @var \App\Entity\User */
        $user = $this->getUser();

        // Commenté pour ignorer les rôles
        if ($user->hasRole('ROLE_AGENCY_HEAD')) {
            $agencies = $agencyRepository->findBy(['user' => $user->getId()]);

            $queryBuilder = $agencyRepository->createQueryBuilder('a')
                ->where('a.user = :user')
                ->setParameter('user', $user->getId());

            $pagination = $paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page', 1),
                8
            );
        } else {
            $agencies = $agencyRepository->findAll();

            $queryBuilder = $agencyRepository->createQueryBuilder('a');

            $pagination = $paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page', 1),
                8
            );
        }

        return $this->render('agency/index.html.twig', [
            'user' => $user,
            'agencies' => $agencies,
            'agencies' => $pagination,
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

            return $this->redirectToRoute('agency_index');
        }

        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if ($user->hasRole('ROLE_AGENCY_HEAD')) {
            $users = $userRepository->findBy(['id' => $user->getId()]);
        } else {
            $users = $this->userGetter->getUsersAgenciesHead();
        }

        return $this->render('agency/create.html.twig', [
            'users' => $users,
        ]);
    }

    

    #[Route('/agencies/{id}', name: 'agency_show', methods: ['GET'])]
    public function show(Request $request, Agency $agency, PaginatorInterface $paginator): Response
    {
        $vehicles = $agency->getVehicles();

        $pagination = $paginator->paginate(
            $vehicles,
            $request->query->getInt('page', 1),
            8
        );

        return $this->render('agency/show.html.twig', [
            'agency' => $agency,
            'pagination' => $pagination,
        ]);
    }

    #[Route('/agencies/{id}/edit', name: 'agency_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Agency $agency, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        if ($request->isMethod('POST')) {
            $data = $request->request->all();

            if (empty($data['label']) || empty($data['address']) || empty($data['city']) || empty($data['zip_code']) || empty($data['user_id'])) {
                $this->addFlash('error', 'Tous les champs requis doivent être remplis.');
                return $this->redirectToRoute('agency_edit', ['id' => $agency->getId()]);
            }

            $existingAgency = $entityManager->getRepository(Agency::class)->findOneBy(['user' => $data['user_id']]);
            if ($existingAgency && $existingAgency->getId() !== $agency->getId()) {
                $this->addFlash('error', 'Cet utilisateur est déjà associé à une autre agence.');
                return $this->redirectToRoute('agency_edit', ['id' => $agency->getId()]);
            }

            $agency->setLabel($data['label']);
            $agency->setAddress($data['address']);
            $agency->setCity($data['city']);
            $agency->setZipCode($data['zip_code']);
            $agency->setUser($userRepository->find($data['user_id']));

            $entityManager->flush();

            $this->addFlash('success', 'Agence mise à jour avec succès.');
            return $this->redirectToRoute('agency_index');
        }

        return $this->render('agency/edit.html.twig', [
            'agency' => $agency,
            'users' => $this->userGetter->getUsersAgenciesHead(),
        ]);
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
