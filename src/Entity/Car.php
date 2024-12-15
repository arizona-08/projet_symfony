<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarRepository::class)]
class Car extends Vehicle
{
    #[ORM\Column(nullable: true)]
    private ?bool $fourWheel = null;
    
    public function isFourWheel(): ?bool
    {
        return $this->fourWheel;
    }

    public function setFourWheel(?bool $fourWheel): static
    {
        $this->fourWheel = $fourWheel;

        return $this;
    }
}
