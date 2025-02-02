<?php

namespace App\Controller;

use App\Entity\Location;
use App\Entity\Status;
use App\Entity\Vehicle;
use App\Repository\LocationRepository;
use App\Repository\UserRepository;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Service\WeatherService;
use App\Utils\UsersGetter;

class HomeController extends AbstractController
{
    private $weatherService;
    private UsersGetter $userGetter;

    public function __construct(WeatherService $weatherService, UserRepository $userRepository)
    {
        $this->weatherService = $weatherService;
        $this->userGetter = new UsersGetter($userRepository);
    }


    #[Route('/', name: 'home', methods: ['GET'])]
    public function index(WeatherService $weatherService): Response
    {
        $weatherData = $weatherService->getWeatherData('Paris');
        return $this->render('index.html.twig',[
            'weather' => $weatherData,
        ]);
    }

    #[Route('/dashboard/commande/{id}', name: 'dashboard_add_to_order', methods: ['GET'])]
    public function addToOrder(
        Vehicle $vehicle,
        SessionInterface $session,
        Request $request
    ): Response {
        $selectedVehicles = $session->get('selected_vehicles', []);

        if (!in_array($vehicle->getId(), $selectedVehicles)) {
            $selectedVehicles[] = $vehicle->getId();
            $session->set('selected_vehicles', $selectedVehicles);
        }

        return $this->redirectToRoute('dashboard', $request->query->all());
    }

    #[Route('/dashboard', name: 'dashboard', methods: ['GET'])]
    public function dashboard(
        Request $request,
        PaginatorInterface $paginator,
        VehicleRepository $vehicleRepository,
        LocationRepository $locationRepository,
        SessionInterface $session
    ): Response {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $selectedVehicleIds = $session->get('selected_vehicles', []);
        $selectedVehicles = $vehicleRepository->findBy(['id' => $selectedVehicleIds]);

        $vehiclesAvailability = [];

        foreach ($selectedVehicles as $vehicle) {
            $reservations = $locationRepository->findReservationsForVehicle($vehicle->getId());

            if (empty($reservations)) {
                $vehiclesAvailability[$vehicle->getId()] = 'Toutes les dates sont disponibles pour ce véhicule.';
            } else {
                $periods = array_map(function ($reservation) {
                    return sprintf(
                        'du %s au %s',

                        $reservation->getStartDate()->format('d/m/Y'), // Utilisation des méthodes getter
            $reservation->getEndDate()->format('d/m/Y')
                    );
                }, $reservations);

                $vehiclesAvailability[$vehicle->getId()] = sprintf(
                    'Ce véhicule est réservé pour les dates suivantes : %s. Veuillez choisir d\'autres dates.',
                    implode(', ', $periods)
                );
            }
        }

        $startDate = $request->query->get('start_date', (new \DateTime())->format('Y-m-d'));
        $endDate = $request->query->get('end_date', (new \DateTime('+1 day'))->format('Y-m-d'));

        $startDateTime = new \DateTime($startDate);
        $endDateTime = new \DateTime($endDate);
        $diffDays = $startDateTime->diff($endDateTime)->days;

        $totalPrice = 0;
        foreach ($selectedVehicles as $vehicle) {
            $totalPrice += $vehicle->getPricePerDay() * $diffDays;
        }

        $queryBuilder = $vehicleRepository->createQueryBuilder('v');
        //affiche que les véhicules de l'agence de l'utilisateur si rôle agency_head
        if($user->hasRole('ROLE_AGENCY_HEAD')){
            $queryBuilder->andWhere('v.agency = :agency')
                ->setParameter('agency', $user->getAgencies()[0]);
        }
        
        $search = $request->query->get('search');
        $brand = $request->query->get('brand');
        $price = $request->query->get('price');

        if ($search) {
            $queryBuilder->andWhere('v.model LIKE :search OR v.marque LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }
        if ($brand) {
            $queryBuilder->andWhere('v.marque = :brand')
                ->setParameter('brand', $brand);
        }
        if ($price) {
            $queryBuilder->andWhere('v.pricePerDay <= :price')
                ->setParameter('price', $price);
        }

        $vehicles = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1),
            8
        );

        return $this->render('dashboard.html.twig', [
            'user' => $user,
            'clients' => $this->userGetter->getClientsUsers(),
            'vehicles' => $vehicles,
            'selectedVehicles' => $selectedVehicles,
            'totalPrice' => $totalPrice,
            'brands' => $vehicleRepository->getAvailableBrands(),
            'pagination' => $vehicles,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'vehiclesAvailability' => $vehiclesAvailability,
        ]);
    }


    #[Route('/validate', name: 'app_location_validate', methods: ['POST'])]
    public function validateOrder(
        Request $request,
        EntityManagerInterface $entityManager,
        SessionInterface $session,
        VehicleRepository $vehicleRepository,
        LocationRepository $locationRepository,
        UserRepository $userRepository
    ): Response {
        $selectedVehicleIds = $session->get('selected_vehicles', []);
        $selectedVehicles = $vehicleRepository->findBy(['id' => $selectedVehicleIds]);

        if (empty($selectedVehicles)) {
            $this->addFlash('error', 'Aucun véhicule sélectionné pour la commande.');
            return $this->redirectToRoute('dashboard');
        }

        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour valider une commande.');
            return $this->redirectToRoute('dashboard');
        }

        $client = $request->request->get('client'); // retourne l'id du client en string
        $clientObject = $userRepository->find((int)$client);
        $startDate = new \DateTime($request->request->get('start_date'));
        $endDate = new \DateTime($request->request->get('end_date'));

        if (!$startDate || !$endDate) {
            $this->addFlash('error', 'Veuillez renseigner les dates de début et de fin.');
            return $this->redirectToRoute('dashboard');
        }

        if($user->hasRole('ROLE_AGENCY_HEAD') && !$client) {
            $this->addFlash('error', 'Veuillez sélectionner un client.');
            return $this->redirectToRoute('dashboard');
        }

        foreach ($selectedVehicles as $vehicle) {
            if (!$locationRepository->isVehicleAvailableDuringPeriod($vehicle->getId(), $startDate, $endDate)) {
                $this->addFlash(
                    'error',
                    sprintf(
                        'Le véhicule %s %s est déjà réservé entre le %s et le %s.',
                        $vehicle->getMarque(),
                        $vehicle->getModel(),
                        $startDate->format('d/m/Y'),
                        $endDate->format('d/m/Y')
                    )
                );
                return $this->redirectToRoute('dashboard');
            }
        }

        $location = new Location();
        $user->hasRole('ROLE_AGENCY_HEAD') ? $location->setUser($clientObject) : $location->setUser($user);
        $location->setStartDate($startDate);
        $location->setEndDate($endDate);

        foreach ($selectedVehicles as $vehicle) {
            $location->addVehicle($vehicle);
        }

        $location->setCreatedAt(new \DateTimeImmutable());

        $entityManager->persist($location);
        $entityManager->flush();

        $session->remove('selected_vehicles');

        $this->addFlash('success', 'Votre commande a été validée avec succès.');
        if($user->hasRole('ROLE_AGENCY_HEAD')){
            return $this->redirectToRoute('app_location_index');
        }
        return $this->redirectToRoute('app_my_locations');
    }


    #[Route('/dashboard/remove/{id}', name: 'dashboard_remove_from_order', methods: ['GET'])]
    public function removeFromOrder(Vehicle $vehicle, SessionInterface $session): Response
    {
        $selectedVehicles = $session->get('selected_vehicles', []);
        $selectedVehicles = array_diff($selectedVehicles, [$vehicle->getId()]);
        $session->set('selected_vehicles', $selectedVehicles);

        return $this->redirectToRoute('dashboard');
    }
}
