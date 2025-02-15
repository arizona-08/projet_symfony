<?php

namespace App\Repository;

use App\Entity\Vehicle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @extends ServiceEntityRepository<Vehicle>
 */
class VehicleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicle::class);
    }

    public function getAvailableBrands(): array
    {
        return $this->createQueryBuilder('v')
            ->select('DISTINCT v.marque')
            ->getQuery()
            ->getResult(Query::HYDRATE_SCALAR_COLUMN);
    }


}
