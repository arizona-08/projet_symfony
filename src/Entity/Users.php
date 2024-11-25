<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
class Users
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $email_verified_at = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 100)]
    private ?string $remember_token = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updated_at = null;

    /**
     * @var Collection<int, roles>
     */
    #[ORM\OneToMany(targetEntity: roles::class, mappedBy: 'users')]
    private Collection $roles;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?orders $orders = null;

    /**
     * @var Collection<int, agencies>
     */
    #[ORM\OneToMany(targetEntity: agencies::class, mappedBy: 'users')]
    private Collection $agencies;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->agencies = new ArrayCollection();
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

    public function getEmailVerifiedAt(): ?\DateTimeInterface
    {
        return $this->email_verified_at;
    }

    public function setEmailVerifiedAt(\DateTimeInterface $email_verified_at): static
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

    public function setRememberToken(string $remember_token): static
    {
        $this->remember_token = $remember_token;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection<int, roles>
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function addRole(roles $role): static
    {
        if (!$this->roles->contains($role)) {
            $this->roles->add($role);
            $role->setUsers($this);
        }

        return $this;
    }

    public function removeRole(roles $role): static
    {
        if ($this->roles->removeElement($role)) {
            // set the owning side to null (unless already changed)
            if ($role->getUsers() === $this) {
                $role->setUsers(null);
            }
        }

        return $this;
    }

    public function getOrders(): ?orders
    {
        return $this->orders;
    }

    public function setOrders(?orders $orders): static
    {
        $this->orders = $orders;

        return $this;
    }

    /**
     * @return Collection<int, agencies>
     */
    public function getAgencies(): Collection
    {
        return $this->agencies;
    }

    public function addAgency(agencies $agency): static
    {
        if (!$this->agencies->contains($agency)) {
            $this->agencies->add($agency);
            $agency->setUsers($this);
        }

        return $this;
    }

    public function removeAgency(agencies $agency): static
    {
        if ($this->agencies->removeElement($agency)) {
            // set the owning side to null (unless already changed)
            if ($agency->getUsers() === $this) {
                $agency->setUsers(null);
            }
        }

        return $this;
    }
}
