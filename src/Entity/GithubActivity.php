<?php

namespace App\Entity;

use App\Repository\GithubActivityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GithubActivityRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class GithubActivity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Project::class, inversedBy="githubActivity", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $project;

    /**
     * @ORM\Column(type="integer")
     */
    private $totalCommits;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $lastCommit = [];

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    public function __construct()
    {
        $this->totalCommits = 0;
        $this->createdAt    = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getTotalCommits(): ?int
    {
        return $this->totalCommits;
    }

    public function setTotalCommits(int $totalCommits): self
    {
        $this->totalCommits = $totalCommits;

        return $this;
    }

    public function getLastCommit(): ?array
    {
        return $this->lastCommit;
    }

    public function setLastCommit(?array $lastCommit): self
    {
        $this->lastCommit = $lastCommit;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @ORM\PreUpdate
     */
    public function update()
    {
        $this->updatedAt = new \DateTime();
    }
}
