<?php

namespace App\Entity;

use App\Repository\CacheRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CacheRepository::class)]
class Cache
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $value = null;

    #[ORM\Column]
    private ?int $expiration = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

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
