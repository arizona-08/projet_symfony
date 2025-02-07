<?php

namespace App\EventListener;

use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use App\Entity\Location;
use App\Entity\Status;
use App\Entity\Vehicle;

class LocationEventListener
{
    private array $vehiclesToUpdate = [];

    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof Location) {
            foreach ($entity->getVehicles() as $vehicle) {
                $this->vehiclesToUpdate[] = $vehicle;

                $vehicle->setStatus($this->determineVehicleStatus($entity));
            }
        }
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof Location) {
            foreach ($entity->getVehicles() as $vehicle) {
                $this->vehiclesToUpdate[] = $vehicle;

                $vehicle->setStatus($this->determineVehicleStatus($entity));
            }
        }
    }

    public function postFlush(PostFlushEventArgs $args): void
    {
        if (empty($this->vehiclesToUpdate)) {
            return;
        }

        $entityManager = $args->getObjectManager();

        foreach ($this->vehiclesToUpdate as $vehicle) {
            $entityManager->persist($vehicle);
        }

        $this->vehiclesToUpdate = [];
        $entityManager->flush();
    }

    private function determineVehicleStatus(Location $location): ?Status
    {
        return $location->getStartDate() <= new \DateTime() && $location->getEndDate() >= new \DateTime()
            ? new Status('Réservé')
            : new Status('Disponible');
    }
}
