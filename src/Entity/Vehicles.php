<?php

namespace App\Entity;

use App\Repository\VehiclesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VehiclesRepository::class)]
class Vehicles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $model = null;

    #[ORM\Column(length: 255)]
    private ?string $marque = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $last_maintenance = null;

    #[ORM\Column]
    private ?int $nb_kilometre = null;

    #[ORM\Column]
    private ?int $nb_serie = null;

    #[ORM\ManyToOne(inversedBy: 'vehicles')]
    private ?Agencies $agencies = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): static
    {
        $this->marque = $marque;

        return $this;
    }

    public function getLastMaintenance(): ?\DateTimeInterface
    {
        return $this->last_maintenance;
    }

    public function setLastMaintenance(\DateTimeInterface $last_maintenance): static
    {
        $this->last_maintenance = $last_maintenance;

        return $this;
    }

    public function getNbKilometre(): ?int
    {
        return $this->nb_kilometre;
    }

    public function setNbKilometre(int $nb_kilometre): static
    {
        $this->nb_kilometre = $nb_kilometre;

        return $this;
    }

    public function getNbSerie(): ?int
    {
        return $this->nb_serie;
    }

    public function setNbSerie(int $nb_serie): static
    {
        $this->nb_serie = $nb_serie;

        return $this;
    }

    public function getAgencies(): ?Agencies
    {
        return $this->agencies;
    }

    public function setAgencies(?Agencies $agencies): static
    {
        $this->agencies = $agencies;

        return $this;
    }
}
