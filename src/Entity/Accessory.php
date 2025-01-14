<?php

namespace App\Entity;

use App\Repository\AccessoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccessoryRepository::class)]
class Accessory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Kit>
     */
    #[ORM\ManyToMany(targetEntity: Kit::class, mappedBy: 'accessory')]
    private Collection $kits;

    public function __construct()
    {
        $this->kits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Kit>
     */
    public function getKits(): Collection
    {
        return $this->kits;
    }

    public function addKit(Kit $kit): static
    {
        if (!$this->kits->contains($kit)) {
            $this->kits->add($kit);
            $kit->addAccessory($this);
        }

        return $this;
    }

    public function removeKit(Kit $kit): static
    {
        if ($this->kits->removeElement($kit)) {
            $kit->removeAccessory($this);
        }

        return $this;
    }
}
