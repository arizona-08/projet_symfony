<?php

namespace App\Entity;

use App\Repository\KitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: KitRepository::class)]
class Kit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private array $accessory = [];

    /**
     * @var Collection<int, Config>
     */
    #[ORM\OneToMany(targetEntity: Config::class, mappedBy: 'kit')]
    private Collection $configs;

    public function __construct()
    {
        $this->configs = new ArrayCollection();
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

    public function getAccessory(): array
    {
        return $this->accessory;
    }

    public function setAccessory(array $accessory): static
    {
        $this->accessory = $accessory;

        return $this;
    }

    /**
     * @return Collection<int, Config>
     */
    public function getConfigs(): Collection
    {
        return $this->configs;
    }

    public function addConfig(Config $config): static
    {
        if (!$this->configs->contains($config)) {
            $this->configs->add($config);
            $config->setKit($this);
        }

        return $this;
    }

    public function removeConfig(Config $config): static
    {
        if ($this->configs->removeElement($config)) {
            // set the owning side to null (unless already changed)
            if ($config->getKit() === $this) {
                $config->setKit(null);
            }
        }

        return $this;
    }
}
