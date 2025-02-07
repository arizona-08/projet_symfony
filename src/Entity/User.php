<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'Il y a déjà un compte avec cet email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $email_verified_at = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $remember_token = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Agency::class, cascade: ['persist', 'remove'])]
    private Collection $agencies;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    /**
     * @var Collection<int, Config>
     */
    #[ORM\OneToMany(targetEntity: Config::class, mappedBy: 'client')]
    private Collection $configs;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Location::class, cascade: ['persist', 'remove'])]
    private Collection $locations;

    /**
     * @var Collection<int, Feedback>
     */
    #[ORM\OneToMany(targetEntity: Feedback::class, mappedBy: 'client')]
    private Collection $feedback;


    public function __construct()
    {
        $this->agencies = new ArrayCollection();

        $this->configs = new ArrayCollection();

        $this->locations = new ArrayCollection();
        $this->feedback = new ArrayCollection();

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getEmailVerifiedAt(): ?\DateTimeImmutable
    {
        return $this->email_verified_at;
    }

    public function setEmailVerifiedAt(\DateTimeImmutable $email_verified_at): static
    {
        $this->email_verified_at = $email_verified_at;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getRememberToken(): ?string
    {
        return $this->remember_token;
    }

    public function setRememberToken(?string $remember_token): static
    {
        $this->remember_token = $remember_token;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * @return Collection<int, Agency>
     */
    public function getAgencies(): Collection
    {
        return $this->agencies;
    }

    public function addAgency(Agency $agency): static
    {
        if (!$this->agencies->contains($agency)) {
            $this->agencies->add($agency);
            $agency->setUser($this);
        }

        return $this;
    }

    public function removeAgency(Agency $agency): static
    {
        if ($this->agencies->removeElement($agency)) {
            if ($agency->getUser() === $this) {
                $agency->setUser(null);
            }
        }

        return $this;
    }

    /**
     * The public representation of the user (e.g. a username, an email address, etc.)
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
 

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection<int, Location>
     */
    public function getLocations(): Collection
    {
        return $this->locations;
    }

    public function addLocation(Location $location): self
    {
        if (!$this->locations->contains($location)) {
            $this->locations->add($location);
            $location->setUser($this);
        }

        return $this;
    }

    public function removeLocation(Location $location): self
    {
        if ($this->locations->removeElement($location)) {
            if ($location->getUser() === $this) {
                $location->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {

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
            $config->setClient($this);
        }

        return $this;
    }

    public function removeConfig(Config $config): static
    {
        if ($this->configs->removeElement($config)) {
            if ($config->getClient() === $this) {
                $config->setClient(null);
            }
        }

        return $this;
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles);
    }

    /**
     * @return Collection<int, Feedback>
     */
    public function getFeedback(): Collection
    {
        return $this->feedback;
    }

    public function addFeedback(Feedback $feedback): static
    {
        if (!$this->feedback->contains($feedback)) {
            $this->feedback->add($feedback);
            $feedback->setClient($this);
        }

        return $this;
    }

    public function removeFeedback(Feedback $feedback): static
    {
        if ($this->feedback->removeElement($feedback)) {
            if ($feedback->getClient() === $this) {
                $feedback->setClient(null);
            }
        }

        return $this;
    }
}
