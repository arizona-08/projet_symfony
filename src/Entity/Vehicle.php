<?php

namespace App\Entity;

use App\Repository\VehicleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\InheritanceType;

#[InheritanceType('JOINED')]
#[DiscriminatorColumn(name: 'discr', type: 'string')]
#[DiscriminatorMap(['car' => Car::class, 'motorcycle' => Motorcycle::class])]
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

    #[ORM\Column(type: 'integer', nullable: false)]
    private $nbKilometrage = null;

    #[ORM\Column(type: 'string', length: 255)]
    private $nbSerie;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private $pricePerDay;

    #[ORM\ManyToMany(targetEntity: Location::class, mappedBy: 'vehicles')]
    private Collection $locations;


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $vehicleFuelType = null;

    #[ORM\Column(nullable: true)]
    private ?int $trunk = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $dimension = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbr_place = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbr_door = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, nullable: true)]
    private ?string $consumption_max = null;

    #[ORM\Column(nullable: true)]
    private ?int $critair = null;

    #[ORM\Column(nullable: true)]
    private ?int $hp = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $color = null;

    #[ORM\Column(type:"json", nullable: true)]
    private ?array $equipment = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $gearBoxType = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $year = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $vehicleType = null;

    #[ORM\ManyToOne(targetEntity: Agency::class, inversedBy: 'vehicles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Agency $agency = null;

    #[ORM\ManyToOne(inversedBy: 'vehicles')]
    private ?Supplier $supplier = null;

    #[ORM\OneToOne(mappedBy: 'vehicle', cascade: ['persist', 'remove'])]
    private ?Config $config = null;

    #[ORM\ManyToOne(targetEntity: Status::class, cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?Status $status = null;

    public function __construct()
    {
        $this->locations = new ArrayCollection();
    }

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

    public function getLocations(): Collection
    {
        return $this->locations;
    }

    public function addLocation(Location $location): self
    {
        if (!$this->locations->contains($location)) {
            $this->locations->add($location);
            $location->addVehicle($this);
        }

        return $this;
    }

    public function removeLocation(Location $location): self
    {
        if ($this->locations->removeElement($location)) {
            $location->removeVehicle($this); 
        }

        return $this;
    }


    public function getVehicleFuelType(): ?string
    {
        return $this->vehicleFuelType;
    }

    public function setVehicleFuelType(string $vehicleFuelType): static
    {
        $this->vehicleFuelType = $vehicleFuelType;

        return $this;
    }

    public function getTrunk(): ?int
    {
        return $this->trunk;
    }

    public function setTrunk(int $trunk): static
    {
        $this->trunk = $trunk;

        return $this;
    }

    public function getDimension(): ?string
    {
        return $this->dimension;
    }

    public function setDimension(string $dimension): static
    {
        $this->dimension = $dimension;

        return $this;
    }

    public function getNbrPlace(): ?int
    {
        return $this->nbr_place;
    }

    public function setNbrPlace(int $nbr_place): static
    {
        $this->nbr_place = $nbr_place;

        return $this;
    }

    public function getNbrDoor(): ?int
    {
        return $this->nbr_door;
    }

    public function setNbrDoor(int $nbr_door): static
    {
        $this->nbr_door = $nbr_door;

        return $this;
    }

    public function getConsumptionMax(): ?string
    {
        return $this->consumption_max;
    }

    public function setConsumptionMax(?string $consumption_max): static
    {
        $this->consumption_max = $consumption_max;

        return $this;
    }

    public function getCritair(): ?int
    {
        return $this->critair;
    }

    public function setCritair(?int $critair): static
    {
        $this->critair = $critair;

        return $this;
    }

    public function getHp(): ?int
    {
        return $this->hp;
    }

    public function setHp(?int $hp): static
    {
        $this->hp = $hp;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getEquipment(): ?array
    {
        return $this->equipment;
    }

    public function setEquipment(?array $equipment): static
    {
        $this->equipment = $equipment;

        return $this;
    }

    public function getGearBoxType(): ?string
    {
        return $this->gearBoxType;
    }

    public function setGearBoxType(?string $gearBoxType): static
    {
        $this->gearBoxType = $gearBoxType;

        return $this;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(?string $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getVehicleType(): ?string
    {
        return $this->vehicleType;
    }

    public function setVehicleType(?string $vehicleType): static
    {
        $this->vehicleType = $vehicleType;

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

    public function getSupplier(): ?Supplier
    {
        return $this->supplier;
    }

    public function setSupplier(?Supplier $supplier): static
    {
        $this->supplier = $supplier;

        return $this;
    }


    public function getConfig(): ?Config
    {
        return $this->config;
    }

    public function setConfig(Config $config): static
    {
        if ($config->getVehicle() !== $this) {
            $config->setVehicle($this);
        }

        $this->config = $config;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function isReservedDuring(\DateTimeInterface $start, \DateTimeInterface $end): bool
    {
        foreach ($this->getLocations() as $locations) {
            if (
                ($start < $locations->getEndDate() && $end > $locations->getStartDate())
            ) {
                return true;
            }
        }
        return false;
    }


}
