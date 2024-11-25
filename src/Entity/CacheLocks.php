<?php

namespace App\Entity;

use App\Repository\CacheLocksRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CacheLocksRepository::class)]
class CacheLocks
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $owner = null;

    #[ORM\Column]
    private ?int $expiration = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwner(): ?string
    {
        return $this->owner;
    }

    public function setOwner(string $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getExpiration(): ?int
    {
        return $this->expiration;
    }

    public function setExpiration(int $expiration): static
    {
        $this->expiration = $expiration;

        return $this;
    }
}
