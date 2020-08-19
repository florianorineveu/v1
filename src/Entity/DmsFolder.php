<?php

namespace App\Entity;

use App\Repository\DmsFolderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DmsFolderRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"name", "parent_dms_folder_id"})
 * })
 */
class DmsFolder
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=DmsFolder::class, inversedBy="createdAt")
     */
    private $parentDmsFolder;

    /**
     * @ORM\OneToMany(targetEntity=DmsFolder::class, mappedBy="parentDmsFolder")
     */
    private $dmsFolders;

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
        $this->dmsFolders = new ArrayCollection();
        $this->createdAt  = new \DateTime();
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

    public function getParentDmsFolder(): ?self
    {
        return $this->parentDmsFolder;
    }

    public function setParentDmsFolder(?self $parentDmsFolder): self
    {
        $this->parentDmsFolder = $parentDmsFolder;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getDmsFolders(): Collection
    {
        return $this->dmsFolders;
    }

    public function addDmsFolder(self $dmsFolder): self
    {
        if (!$this->dmsFolders->contains($dmsFolder)) {
            $this->dmsFolders[] = $dmsFolder;
            $dmsFolder->setParentDmsFolder($this);
        }

        return $this;
    }

    public function removeDmsFolder(self $dmsFolder): self
    {
        if ($this->dmsFolders->contains($dmsFolder)) {
            $this->dmsFolders->removeElement($dmsFolder);
            // set the owning side to null (unless already changed)
            if ($dmsFolder->getParentDmsFolder() === $this) {
                $dmsFolder->setParentDmsFolder(null);
            }
        }

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
