<?php

namespace App\Service;

use App\Entity\Vehicle;
use App\Entity\Status;
use Doctrine\ORM\EntityManagerInterface;

class VehicleStatusService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function updateVehicleStatuses(): void
    {
        $vehicles = $this->entityManager->getRepository(Vehicle::class)->findAll();
        $statusReserved = $this->entityManager->getRepository(Status::class)->findOneBy(['name' => 'Réservé']);
        $statusAvailable = $this->entityManager->getRepository(Status::class)->findOneBy(['name' => 'Disponible']);
        $now = new \DateTime();

        foreach ($vehicles as $vehicle) {
            $isReserved = false;

            foreach ($vehicle->getLocation() as $location) {
                if ($location->getStartDate() <= $now && $location->getEndDate() >= $now) {
                    $vehicle->setStatus($statusReserved);
                    $isReserved = true;
                    break;
                }
            }

            if (!$isReserved) {
                $vehicle->setStatus($statusAvailable);
            }

            $this->entityManager->persist($vehicle);
        }

        $this->entityManager->flush();
    }
}

