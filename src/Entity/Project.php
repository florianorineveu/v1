<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProjectRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable
 */
class Project
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $shortDescription;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @Gedmo\Slug(fields={"name"}, unique=true)
     * @ORM\Column(type="string", unique=true, length=128)
     */
    private $slug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @Vich\UploadableField(mapping="projects", fileNameProperty="cover")
     *
     * @var File|null
     */
    private $coverFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cover;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $stack;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $team;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $githubOwner;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $githubRepository;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $githubBranch;

    /**
     * @ORM\OneToOne(targetEntity=GithubActivity::class, mappedBy="project", cascade={"persist", "remove"})
     */
    private $githubActivity;

    /**
     * @ORM\Column(type="boolean")
     */
    private $githubDisplayed;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sort;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

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
        $this->githubDisplayed = false;
        $this->enabled         = false;
        $this->createdAt       = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(?string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function setCoverFile(?File $imageFile = null): void
    {
        $this->coverFile = $imageFile;

        if (null !== $imageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getCoverFile(): ?File
    {
        return $this->coverFile;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function setCover(?string $cover = null): self
    {
        $this->cover = $cover;

        return $this;
    }

    public function getStack(): ?string
    {
        return $this->stack;
    }

    public function setStack(?string $stack): self
    {
        $this->stack = $stack;

        return $this;
    }

    public function getTeam(): ?string
    {
        return $this->team;
    }

    public function setTeam(?string $team): self
    {
        $this->team = $team;

        return $this;
    }

    public function getGithubOwner(): ?string
    {
        return $this->githubOwner;
    }

    public function setGithubOwner(?string $githubOwner): self
    {
        $this->githubOwner = $githubOwner;

        return $this;
    }

    public function getGithubRepository(): ?string
    {
        return $this->githubRepository;
    }

    public function setGithubRepository(?string $githubRepository): self
    {
        $this->githubRepository = $githubRepository;

        return $this;
    }

    public function getGithubBranch(): ?string
    {
        return $this->githubBranch;
    }

    public function setGithubBranch(?string $githubBranch): self
    {
        $this->githubBranch = $githubBranch;

        return $this;
    }

    public function getGithubActivity(): ?GithubActivity
    {
        return $this->githubActivity;
    }

    public function setGithubActivity(GithubActivity $githubActivity): self
    {
        $this->githubActivity = $githubActivity;

        // set the owning side of the relation if necessary
        if ($githubActivity->getProject() !== $this) {
            $githubActivity->setProject($this);
        }

        return $this;
    }

    public function getGithubDisplayed(): ?bool
    {
        return $this->githubDisplayed;
    }

    public function setGithubDisplayed(bool $githubDisplayed): self
    {
        $this->githubDisplayed = $githubDisplayed;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getSort(): ?int
    {
        return $this->sort;
    }

    public function setSort(?int $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

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
