<?php

namespace App\Repository;

use App\Entity\Location;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Location>
 */
class LocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Location::class);
    }

    public function findFinishedByUser(User $user): array
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.user = :user')
            ->andWhere('l.end_date <= :now')
            ->setParameter('user', $user)
            ->setParameter('now', new \DateTime())
            ->getQuery()
            ->getResult();
    }

    public function findOngoingByUser(User $user): array
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.user = :user')
            ->andWhere('l.start_date <= :now')
            ->andWhere('l.end_date >= :now')
            ->setParameter('user', $user)
            ->setParameter('now', new \DateTime())
            ->getQuery()
            ->getResult();
    }

    public function findUpcomingByUser(User $user): array
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.user = :user')
            ->andWhere('l.start_date > :now')
            ->setParameter('user', $user)
            ->setParameter('now', new \DateTime())
            ->getQuery()
            ->getResult();
    }

    public function findAllByUser(User $user): array
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    /**
     *
     * @param int $vehicleId
     * @param \DateTimeInterface $startDate
     * @param \DateTimeInterface $endDate
     * @return bool True si le véhicule est disponible, false sinon.
     */
    public function isVehicleAvailableDuringPeriod(int $vehicleId, \DateTimeInterface $startDate, \DateTimeInterface $endDate, ?int $currentLocationId = null): bool
    {
        $qb = $this->createQueryBuilder('l')
            ->join('l.vehicles', 'v')
            ->where('v.id = :vehicleId')
            ->andWhere('l.start_date < :endDate')
            ->andWhere('l.end_date > :startDate')
            ->setParameter('vehicleId', $vehicleId)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate);

        if ($currentLocationId) {
            $qb->andWhere('l.id != :currentLocationId')
                ->setParameter('currentLocationId', $currentLocationId);
        }

        return $qb->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult() === null;
    }



    public function findActiveReservationForVehicle(int $vehicleId): ?Location
    {
        return $this->createQueryBuilder('l')
            ->join('l.vehicles', 'v')
            ->where('v.id = :vehicleId')
            ->andWhere('l.start_date <= :now')
            ->andWhere('l.end_date >= :now')
            ->setParameter('vehicleId', $vehicleId)
            ->setParameter('now', new \DateTime())
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findReservationsForVehicle(int $vehicleId): array
    {
        $qb = $this->createQueryBuilder('l')
            ->join('l.vehicles', 'v')
            ->where('v.id = :vehicleId')
            ->andWhere('l.end_date >= :now')
            ->setParameter('vehicleId', $vehicleId)
            ->setParameter('now', new \DateTime('today'))
            ->orderBy('l.start_date', 'ASC');

        return $qb->getQuery()->getResult();
    }



}
