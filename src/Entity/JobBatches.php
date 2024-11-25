<?php

namespace App\Entity;

use App\Repository\JobBatchesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JobBatchesRepository::class)]
class JobBatches
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $total_jobs = null;

    #[ORM\Column]
    private ?int $pending_jobs = null;

    #[ORM\Column]
    private ?int $failed_jobs = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $options = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $cancelled_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $finished_at = null;

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

    public function getTotalJobs(): ?int
    {
        return $this->total_jobs;
    }

    public function setTotalJobs(int $total_jobs): static
    {
        $this->total_jobs = $total_jobs;

        return $this;
    }

    public function getPendingJobs(): ?int
    {
        return $this->pending_jobs;
    }

    public function setPendingJobs(int $pending_jobs): static
    {
        $this->pending_jobs = $pending_jobs;

        return $this;
    }

    public function getFailedJobs(): ?int
    {
        return $this->failed_jobs;
    }

    public function setFailedJobs(int $failed_jobs): static
    {
        $this->failed_jobs = $failed_jobs;

        return $this;
    }

    public function getOptions(): ?string
    {
        return $this->options;
    }

    public function setOptions(?string $options): static
    {
        $this->options = $options;

        return $this;
    }

    public function getCancelledAt(): ?\DateTimeInterface
    {
        return $this->cancelled_at;
    }

    public function setCancelledAt(?\DateTimeInterface $cancelled_at): static
    {
        $this->cancelled_at = $cancelled_at;

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

    public function getFinishedAt(): ?\DateTimeInterface
    {
        return $this->finished_at;
    }

    public function setFinishedAt(\DateTimeInterface $finished_at): static
    {
        $this->finished_at = $finished_at;

        return $this;
    }
}
