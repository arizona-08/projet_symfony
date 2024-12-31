<?php

namespace App\Controller;

use App\Entity\Location;
use App\Entity\Vehicle;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class HomeController extends AbstractController
{

    #[Route('/', name: 'home', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('index.html.twig');
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
        SessionInterface $session
    ): Response {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $selectedVehicleIds = $session->get('selected_vehicles', []);
        $selectedVehicles = $vehicleRepository->findBy(['id' => $selectedVehicleIds]);

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
            'vehicles' => $vehicles,
            'selectedVehicles' => $selectedVehicles,
            'totalPrice' => $totalPrice,
            'brands' => $vehicleRepository->getAvailableBrands(),
            'pagination' => $vehicles,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
    }



    #[Route('/validate', name: 'app_location_validate', methods: ['POST'])]
    public function validateOrder(
        Request $request,
        EntityManagerInterface $entityManager,
        SessionInterface $session,
        VehicleRepository $vehicleRepository
    ): Response {

        $selectedVehicleIds = $session->get('selected_vehicles', []);
        $selectedVehicles = $vehicleRepository->findBy(['id' => $selectedVehicleIds]);

        if (empty($selectedVehicles)) {
            $this->addFlash('error', 'Aucun véhicule sélectionné pour la commande.');
            return $this->redirectToRoute('dashboard');
        }

        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour valider une commande.');
            return $this->redirectToRoute('dashboard');
        }

        $location = new Location();

        $startDate = $request->request->get('start_date');
        $endDate = $request->request->get('end_date');

        if (!$startDate || !$endDate) {
            $this->addFlash('error', 'Veuillez renseigner les dates de début et de fin.');
            return $this->redirectToRoute('dashboard');
        }

        $location->setStartDate(new \DateTime($startDate));
        $location->setEndDate(new \DateTime($endDate));
        $location->setUser($user);

        foreach ($selectedVehicles as $vehicle) {
            $location->addVehicle($vehicle);
        }

        $location->setCreatedAt(new \DateTimeImmutable());

        $entityManager->persist($location);
        $entityManager->flush();

        $session->remove('selected_vehicles');

        $this->addFlash('success', 'Votre commande a été validée avec succès.');
        return $this->redirectToRoute('app_location_index');
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
