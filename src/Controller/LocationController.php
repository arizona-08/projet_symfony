<?php

namespace App\Controller;

use App\Entity\Location;
use App\Entity\User;
use App\Entity\Vehicle;
use App\Form\LocationType;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Form\VipLocationType;
use App\Entity\Config;
use Symfony\Component\Validator\Constraints\Collection;

#[Route('/location')]
final class LocationController extends AbstractController
{

    #[Route('/my-locations', name: 'app_my_locations', methods: ['GET'])]
    public function myLocations(LocationRepository $locationRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour voir vos commandes.');
        }

        $locations = $locationRepository->findBy(['user' => $user]);

        $locationsWithTotalPrice = [];
        foreach ($locations as $location) {
            $totalPrice = 0;
            foreach ($location->getVehicle() as $vehicle) {
                $totalPrice += $vehicle->getPricePerDay();
            }

            $locationsWithTotalPrice[] = [
                'location' => $location,
                'totalPrice' => $totalPrice,
            ];
        }

        return $this->render('location/my_locations.html.twig', [
            'locationsWithTotalPrice' => $locationsWithTotalPrice,
        ]);
    }

    #[Route(name: 'app_location_index', methods: ['GET'])]
    public function index(LocationRepository $locationRepository, PaginatorInterface $paginator, Request $request): Response
    {
        if(!$this->isGranted('ROLE_AGENCY_HEAD')){
            if(!$this->isGranted('ROLE_ORDER_MANAGER')){
                throw $this->createAccessDeniedException('Vous n\'avez pas les droits pour accéder à cette page.');
            }
        }

        // if the user is an agency head, we only show the locations of the vehicles of his agency
        
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $locations = $locationRepository->findAll();
        $collection = $locations;
        $userAgencyVehiclesLocation = [];

        if($user->hasRole('ROLE_AGENCY_HEAD')){
            $agencyLabel = $user->getAgencies()[0]->getLabel();

            foreach($locations as $location){
                foreach($location->getVehicle() as $vehicle){
                    if($vehicle->getAgency()->getLabel() == $agencyLabel){
                        if (!in_array($location->getId(), array_map(fn($loc) => $loc->getId(), $userAgencyVehiclesLocation))) {
                            $userAgencyVehiclesLocation[] = $location;
                        }
                    }
                }
            }

            $collection = $userAgencyVehiclesLocation;
        }


        $pagination = $paginator->paginate(
            $collection,
            $request->query->getInt('page', 1),
            8
        );

        foreach ($pagination as $location) {
            $totalPrice = 0;
            foreach ($location->getVehicle() as $vehicle) {
                $totalPrice += $vehicle->getPricePerDay();
            }
            $location->totalPrice = $totalPrice;
        }

        return $this->render('location/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }


    #[Route('/new', name: 'app_location_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        SessionInterface $session,
        PaginatorInterface $paginator
    ): Response {
        $location = new Location();

        $users = $entityManager->getRepository(User::class)->findAll();

        $vehiclesQuery = $entityManager->getRepository(Vehicle::class)->createQueryBuilder('v');

        $brands = array_unique(array_map(
            fn($vehicle) => $vehicle->getMarque(),
            $entityManager->getRepository(Vehicle::class)->findAll()
        ));

        $search = $request->query->get('search');
        $brand = $request->query->get('brand');
        $price = $request->query->get('price');

        if ($search) {
            $vehiclesQuery->andWhere('v.model LIKE :search OR v.marque LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        if ($brand) {
            $vehiclesQuery->andWhere('v.marque = :brand')
                ->setParameter('brand', $brand);
        }

        if ($price) {
            $vehiclesQuery->andWhere('v.pricePerDay <= :price')
                ->setParameter('price', $price);
        }

        $vehicles = $paginator->paginate(
            $vehiclesQuery->getQuery(),
            $request->query->getInt('page', 1),
            8
        );

        $newLocationVehiclesIds = $session->get('new_location_vehicles', []);
        $selectedVehicles = $entityManager->getRepository(Vehicle::class)->findBy(['id' => $newLocationVehiclesIds]);

        if ($request->isMethod('POST')) {
            $userId = $request->request->get('user_id');
            $user = $entityManager->getRepository(User::class)->find($userId);

            if (!$user) {
                $this->addFlash('error', 'Utilisateur invalide.');
                return $this->redirectToRoute('app_location_new');
            }

            $startDate = $request->request->get('start_date');
            $endDate = $request->request->get('end_date');

            if (!$startDate || !$endDate) {
                $this->addFlash('error', 'Veuillez renseigner des dates valides.');
                return $this->redirectToRoute('app_location_new');
            }

            $location->setUser($user);
            $location->setStartDate(new \DateTime($startDate));
            $location->setEndDate(new \DateTime($endDate));
            $location->setCreatedAt(new \DateTimeImmutable());

            foreach ($selectedVehicles as $vehicle) {
                $location->addVehicle($vehicle);
            }

            $entityManager->persist($location);
            $entityManager->flush();

            $session->remove('new_location_vehicles');

            $this->addFlash('success', 'Location créée avec succès.');
            return $this->redirectToRoute('app_location_index');
        }

        return $this->render('location/new.html.twig', [
            'location' => $location,
            'pagination' => $vehicles,
            'vehicles' => $vehicles->getItems(),
            'users' => $users,
            'selectedVehicles' => $selectedVehicles,
            'brands' => $brands,
        ]);
    }

    #[Route('/locations/new/{id}', name: 'app_location_new_for_vehicle', methods: ['GET', 'POST'])]
    public function newForVehicle(
        Vehicle $vehicle,
        Request $request,
        EntityManagerInterface $entityManager,
        SessionInterface $session
    ): Response {
        $location = new Location();
        $location->addVehicle($vehicle);

        $form = $this->createForm(LocationType::class, $location);
        $form->handleRequest($request);

        $selectedVehicles = $session->get('selected_vehicles', []);
        if (!in_array($vehicle->getId(), $selectedVehicles)) {
            $selectedVehicles[] = $vehicle->getId();
            $session->set('selected_vehicles', $selectedVehicles);
        }

        $startDate = $request->query->get('start_date');
        $endDate = $request->query->get('end_date');

        if ($form->isSubmitted() && $form->isValid()) {
            $location->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($location);
            $entityManager->flush();

            $this->addFlash('success', 'Commande effectuée avec succès.');
            return $this->redirectToRoute('dashboard');
        }

        return $this->render('location/new_for_vehicle.html.twig', [
            'form' => $form->createView(),
            'vehicle' => $vehicle,
            'selectedVehicles' => $selectedVehicles,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
    }




    #[Route('/{id}', name: 'app_location_show', methods: ['GET'])]
    public function show(Location $location): Response
    {
        /** @var \App\Entity\User user */
        $user = $this->getUser();

        if(!$this->isGranted('ROLE_ORDER_MANAGER') && !$this->isGranted('ROLE_AGENCY_HEAD')){
            if(!$user->getLocations()->contains($location)){
                return $this->redirectToRoute('app_my_locations');
            }
        }
       

        $totalPrice = 0;
        foreach ($location->getVehicle() as $vehicle) {
            $totalPrice += $vehicle->getPricePerDay();
        }

        return $this->render('location/show.html.twig', [
            'location' => $location,
            'totalPrice' => $totalPrice,
        ]);
    }







    #[Route('/{id}/edit', name: 'app_location_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Location $location,
        EntityManagerInterface $entityManager,
        PaginatorInterface $paginator
    ): Response {

        if(!$this->isGranted('ROLE_AGENCY_HEAD')){
            if(!$this->isGranted('ROLE_ORDER_MANAGER')){
                throw $this->createAccessDeniedException('Vous n\'avez pas les droits pour accéder à cette page.');
            }
        }

        $vehiclesQuery = $entityManager->getRepository(Vehicle::class)->createQueryBuilder('v');
        $search = $request->query->get('search');
        $brand = $request->query->get('brand');
        $price = $request->query->get('price');

        if ($search) {
            $vehiclesQuery->andWhere('v.model LIKE :search OR v.marque LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        if ($brand) {
            $vehiclesQuery->andWhere('v.marque = :brand')
                ->setParameter('brand', $brand);
        }

        if ($price) {
            $vehiclesQuery->andWhere('v.pricePerDay <= :price')
                ->setParameter('price', $price);
        }

        $vehicles = $paginator->paginate(
            $vehiclesQuery->getQuery(),
            $request->query->getInt('page', 1),
            8
        );

        $brands = array_unique(array_map(
            fn($vehicle) => $vehicle->getMarque(),
            $entityManager->getRepository(Vehicle::class)->findAll()
        ));

        if ($request->isMethod('POST')) {
            $startDate = $request->request->get('start_date');
            $endDate = $request->request->get('end_date');

            if ($startDate && $endDate) {
                $location->setStartDate(new \DateTime($startDate));
                $location->setEndDate(new \DateTime($endDate));
                $entityManager->flush();

                return $this->redirectToRoute('app_location_edit', ['id' => $location->getId()]);
            }
        }

        $form = $this->createForm(LocationType::class, $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Location modifiée avec succès.');
            return $this->redirectToRoute('app_location_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('location/edit.html.twig', [
            'location' => $location,
            'form' => $form->createView(),
            'vehicles' => $vehicles,
            'brands' => $brands,
        ]);
    }





    #[Route('/{id}', name: 'app_location_delete', methods: ['POST'])]
    public function delete(Request $request, Location $location, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $location->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($location);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_location_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/new/add-vehicle/{vehicle_id}', name: 'app_location_add_vehicle', methods: ['GET', 'POST'])]
    public function addVehicle(
        Request $request,
        int $vehicle_id,
        EntityManagerInterface $entityManager,
        SessionInterface $session
    ): Response {
        $vehicle = $entityManager->getRepository(Vehicle::class)->find($vehicle_id);

        if (!$vehicle) {
            throw $this->createNotFoundException('Véhicule non trouvé.');
        }

        $newLocationVehicles = $session->get('new_location_vehicles', []);
        if (!in_array($vehicle_id, $newLocationVehicles)) {
            $newLocationVehicles[] = $vehicle_id;
            $session->set('new_location_vehicles', $newLocationVehicles);
        }

        return $this->redirectToRoute('app_location_new', $request->query->all());
    }

    #[Route('/new/remove-vehicle/{vehicle_id}', name: 'app_location_remove_vehicle', methods: ['GET', 'POST'])]
    public function removeVehicle(
        int $vehicle_id,
        SessionInterface $session
    ): Response {
        $newLocationVehicles = $session->get('new_location_vehicles', []);
        if (($key = array_search($vehicle_id, $newLocationVehicles)) !== false) {
            unset($newLocationVehicles[$key]);
            $session->set('new_location_vehicles', $newLocationVehicles);
        }

        return $this->redirectToRoute('app_location_new');
    }

    #[Route('/{id}/add-vehicle/{vehicle_id}', name: 'app_location_add_vehicle_existing', methods: ['GET', 'POST'])]
    public function addVehicleToExisting(
        int $id,
        int $vehicle_id,
        EntityManagerInterface $entityManager
    ): Response {
        $location = $entityManager->getRepository(Location::class)->find($id);
        $vehicle = $entityManager->getRepository(Vehicle::class)->find($vehicle_id);

        if (!$location || !$vehicle) {
            throw $this->createNotFoundException('Location ou véhicule non trouvé.');
        }

        if (!$location->getVehicle()->contains($vehicle)) {
            $location->addVehicle($vehicle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_location_edit', ['id' => $id]);
    }

    #[Route('/{id}/remove-vehicle/{vehicle_id}', name: 'app_location_remove_vehicle_existing', methods: ['GET', 'POST'])]
    public function removeVehicleFromExisting(
        int $id,
        int $vehicle_id,
        EntityManagerInterface $entityManager
    ): Response {
        $location = $entityManager->getRepository(Location::class)->find($id);
        $vehicle = $entityManager->getRepository(Vehicle::class)->find($vehicle_id);

        if (!$location || !$vehicle) {
            throw $this->createNotFoundException('Location ou véhicule non trouvé.');
        }

        if ($location->getVehicle()->contains($vehicle)) {
            $location->removeVehicle($vehicle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_location_edit', ['id' => $id]);
    }

    #[Route('/new/vip/{id}', name: 'app_location_new_vip', methods: ['GET', 'POST'])]
    public function newVipLocation(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {

        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour créer une location VIP.');
        }

        if(!$this->isGranted('ROLE_VIP')){
            throw $this->createAccessDeniedException("Vous devez être VIP pour accéder à cette page.");
        }

        $config = $entityManager->getRepository(Config::class)->find($id);
        if (!$config) {
            throw $this->createNotFoundException('Configuration non trouvée.');
        }

        $location = new Location();
        $location->setUser($user);
        $location->setConfig($config);
        $location->setVip(true);
        $location->setCreatedAt(new \DateTimeImmutable());

        $location->addVehicle($config->getVehicle());

        $form = $this->createForm(VipLocationType::class, $location, [
            'include_vehicle' => false,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($location);
            $entityManager->flush();

            $this->addFlash('success', 'Location VIP créée avec succès.');

            if($user->hasRole('ROLE_VIP')){
                return $this->redirectToRoute('app_my_locations');
            }
            return $this->redirectToRoute('app_location_index');
        }

        return $this->render('location/new_vip.html.twig', [
            'form' => $form->createView(),
            'config' => $config,
        ]);

    }


}
