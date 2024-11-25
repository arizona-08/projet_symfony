<?php

namespace App\Entity;

use App\Repository\FailedJobsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FailedJobsRepository::class)]
class FailedJobs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $connection = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $queue = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $payload = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $exception = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $failed_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConnection(): ?string
    {
        return $this->connection;
    }

    public function setConnection(string $connection): static
    {
        $this->connection = $connection;

        return $this;
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

    public function getException(): ?string
    {
        return $this->exception;
    }

    public function setException(string $exception): static
    {
        $this->exception = $exception;

        return $this;
    }

    public function getFailedAt(): ?\DateTimeInterface
    {
        return $this->failed_at;
    }

    public function setFailedAt(\DateTimeInterface $failed_at): static
    {
        $this->failed_at = $failed_at;

        return $this;
    }
}
