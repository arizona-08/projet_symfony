<?php

namespace App\Entity;

use App\Repository\VehicleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VehicleRepository::class)]
class Vehicle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private $model;

    #[ORM\Column(type: 'string', length: 255)]
    private $marque;

    #[ORM\Column(type: 'date')]
    private $lastMaintenance;

    #[ORM\Column(type: 'integer')]
    private $nbKilometrage;

    #[ORM\Column(type: 'string', length: 255)]
    private $nbSerie;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private $pricePerDay;

    #[ORM\ManyToOne(targetEntity: Agency::class, inversedBy: 'vehicles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Agency $agency = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getLastMaintenance(): ?\DateTimeInterface
    {
        return $this->lastMaintenance;
    }

    public function setLastMaintenance(\DateTimeInterface $lastMaintenance): self
    {
        $this->lastMaintenance = $lastMaintenance;

        return $this;
    }

    public function getNbKilometrage(): ?int
    {
        return $this->nbKilometrage;
    }

    public function setNbKilometrage(int $nbKilometrage): self
    {
        $this->nbKilometrage = $nbKilometrage;

        return $this;
    }

    public function getNbSerie(): ?string
    {
        return $this->nbSerie;
    }

    public function setNbSerie(string $nbSerie): self
    {
        $this->nbSerie = $nbSerie;

        return $this;
    }

    public function getPricePerDay(): ?string
    {
        return $this->pricePerDay;
    }

    public function setPricePerDay(string $pricePerDay): self
    {
        $this->pricePerDay = $pricePerDay;

        return $this;
    }

    public function getAgency(): ?Agency
    {
        return $this->agency;
    }

    public function setAgency(?Agency $agency): self
    {
        $this->agency = $agency;

        return $this;
    }
}
