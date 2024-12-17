<?php

namespace App\Controller;

use App\Entity\Agency;
use App\Entity\Supplier;
use App\Entity\Vehicle;
// use App\Entity\Status;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VehicleController extends AbstractController
{
    #[Route('/vehicles', name: 'vehicle_index', methods: ['GET'])]
    public function index(VehicleRepository $vehicleRepository, Request $request): Response
    {
        $brand = $request->query->get('brand');
        $sortKm = $request->query->get('sort_km');

        $queryBuilder = $vehicleRepository->createQueryBuilder('v');

        if ($brand) {
            $queryBuilder->andWhere('v.marque = :brand')
                ->setParameter('brand', $brand);
        }

        if ($sortKm && in_array($sortKm, ['asc', 'desc'])) {
            $queryBuilder->orderBy('v.nbKilometrage', $sortKm);
        }

        $vehicles = $queryBuilder->getQuery()->getResult();

        if (empty($vehicles)) {
            $this->addFlash('info', 'Aucun véhicule trouvé. Ajoutez des véhicules pour les afficher.');
        }

        return $this->render('vehicle/index.html.twig', [
            'vehicles' => $vehicles,
        ]);
    }


    #[Route('/vehicles/create', name: 'vehicle_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $data = $request->request->all();

            $vehicle = new Vehicle();
            $vehicle->setModel($data['model']);
            $vehicle->setMarque($data['marque']);
            $vehicle->setLastMaintenance(new \DateTime($data['last_maintenance']));
            $vehicle->setNbKilometrage($data['nb_kilometrage']);
            $vehicle->setNbSerie($data['nb_serie']);
            $vehicle->setPricePerDay($data['price_per_day']);

            $agency = $entityManager->getRepository(Agency::class)->find($data['agency_id']);
            // $status = $entityManager->getRepository(Status::class)->find($data['status_id']);
            $supplier = $entityManager->getRepository(Supplier::class)->find($data['supplier_id']);

            $vehicle->setAgency($agency);
            // $vehicle->setStatus($status);
            $vehicle->setSupplier($supplier);

            $entityManager->persist($vehicle);
            $entityManager->flush();

            return $this->redirectToRoute('vehicle_index');
        }

        $agencies = $entityManager->getRepository(Agency::class)->findAll();
        // $statuses = $entityManager->getRepository(Status::class)->findAll();
        $suppliers = $entityManager->getRepository(Supplier::class)->findAll();

        return $this->render('vehicle/create.html.twig', [
            'agencies' => $agencies,
            // 'statuses' => $statuses,
            'suppliers' => $suppliers,
        ]);
    }

    #[Route('/vehicles/{id}/update', name: 'vehicle_update', methods: ['POST', 'PUT'])]
    public function update(Request $request, Vehicle $vehicle, EntityManagerInterface $entityManager): Response
    {
        $data = $request->request->all();

        $vehicle->setModel($data['model']);
        $vehicle->setMarque($data['marque']);
        $vehicle->setLastMaintenance(new \DateTime($data['last_maintenance']));
        $vehicle->setNbKilometrage((int) $data['nb_kilometrage']);
        $vehicle->setNbSerie($data['nb_serie']);
        $vehicle->setPricePerDay((float) $data['price_per_day']);

        $agency = $entityManager->getRepository(Agency::class)->find($data['agency_id']);
        // $status = $entityManager->getRepository(Status::class)->find($data['status_id']);
        $supplier = $entityManager->getRepository(Supplier::class)->find($data['supplier_id']);
        $vehicle->setAgency($agency);
        // $vehicle->setStatus($status);
        $vehicle->setSupplier($supplier);

        $entityManager->flush();

        return $this->redirectToRoute('vehicle_index');
    }


    #[Route('/vehicles/{id}/edit', name: 'vehicle_edit', methods: ['GET', 'POST'])]
    public function edit(Vehicle $vehicle, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $data = $request->request->all();

            $vehicle->setModel($data['model']);
            $vehicle->setMarque($data['marque']);
            $vehicle->setLastMaintenance(new \DateTime($data['last_maintenance']));
            $vehicle->setNbKilometrage($data['nb_kilometrage']);
            $vehicle->setNbSerie($data['nb_serie']);
            $vehicle->setPricePerDay($data['price_per_day']);

            $agency = $entityManager->getRepository(Agency::class)->find($data['agency_id']); // Commenté
            // $status = $entityManager->getRepository(Status::class)->find($data['status_id']); // Commenté
            $supplier = $entityManager->getRepository(Supplier::class)->find($data['supplier_id']); // Commenté

            $vehicle->setAgency($agency);
            // $vehicle->setStatus($status);
            $vehicle->setSupplier($supplier);

            $entityManager->flush();

            return $this->redirectToRoute('vehicle_index');
        }

        $agencies = $entityManager->getRepository(Agency::class)->findAll(); // Commenté
        // $statuses = $entityManager->getRepository(Status::class)->findAll(); // Commenté
        $suppliers = $entityManager->getRepository(Supplier::class)->findAll(); // Commenté

        return $this->render('vehicle/edit.html.twig', [
            'vehicle' => $vehicle,
            'agencies' => $agencies,
            // 'statuses' => $statuses,
            'suppliers' => $suppliers,
        ]);
    }

    #[Route('/vehicles/{id}', name: 'vehicle_show', methods: ['GET'])]
    public function show(Vehicle $vehicle): Response
    {
        return $this->render('vehicle/show.html.twig', [
            'vehicle' => $vehicle,
        ]);
    }


    #[Route('/vehicles/{id}/delete', name: 'vehicle_delete', methods: ['POST'])]
    public function delete(Vehicle $vehicle, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($vehicle);
        $entityManager->flush();

        return $this->redirectToRoute('vehicle_index');
    }

}
