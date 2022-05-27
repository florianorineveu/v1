<?php

namespace App\Entity;

use App\Doctrine\Model\HasInlinedProperties;
use App\Model\TemplateBlock\ConfigurationInterface;
use App\Repository\TemplateBlockRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

#[ORM\Entity(repositoryClass: TemplateBlockRepository::class)]
class TemplateBlock implements HasInlinedProperties
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'integer')]
    private $position;

    #[ORM\Column(type: 'block_configuration')]
    private ConfigurationInterface $configuration;

    #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: 'blocks')]
    private $project;

    private $type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getConfiguration(): ConfigurationInterface
    {
        return $this->configuration;
    }

    public function setConfiguration(ConfigurationInterface $configuration): self
    {
        $this->configuration = $configuration;

        return $this;
    }

    public function getInlinedProperties(): array
    {
        return ['configuration'];
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getType(): string
    {
        $reflection = new \ReflectionClass($this->getConfiguration());

        return $reflection->getName();
    }
}
