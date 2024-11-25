<?php

namespace App\Entity;

use App\Repository\JobsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JobsRepository::class)]
class Jobs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $queue = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $payload = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $attempts = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $reserved_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $available_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQueue(): ?string
    {
        return $this->queue;
    }

    public function setQueue(string $queue): static
    {
        $this->queue = $queue;

        return $this;
    }

    public function getPayload(): ?string
    {
        return $this->payload;
    }

    public function setPayload(string $payload): static
    {
        $this->payload = $payload;

        return $this;
    }

    public function getAttempts(): ?int
    {
        return $this->attempts;
    }

    public function setAttempts(int $attempts): static
    {
        $this->attempts = $attempts;

        return $this;
    }

    public function getReservedAt(): ?\DateTimeInterface
    {
        return $this->reserved_at;
    }

    public function setReservedAt(?\DateTimeInterface $reserved_at): static
    {
        $this->reserved_at = $reserved_at;

        return $this;
    }

    public function getAvailableAt(): ?\DateTimeInterface
    {
        return $this->available_at;
    }

    public function setAvailableAt(\DateTimeInterface $available_at): static
    {
        $this->available_at = $available_at;

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
}
