<?php

namespace App\Entity;

use App\Repository\MotorcycleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MotorcycleRepository::class)]
class Motorcycle extends Vehicle
{
    
}
