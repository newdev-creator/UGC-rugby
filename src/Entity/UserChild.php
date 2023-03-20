<?php

namespace App\Entity;

use App\Repository\UserChildRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: UserChildRepository::class)]
#[Vich\Uploadable]
class UserChild
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[Vich\UploadableField(mapping: 'child_pictures', fileNameProperty: 'childPictureName')]
    private ?File $childPictureFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $childPictureName = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $birthday = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    private ?int $isActive = 1;

    #[ORM\ManyToOne(inversedBy: 'child')]
    private ?User $user = null;

    #[ORM\ManyToOne(fetch: 'EAGER', inversedBy: 'child')]
    private ?Category $category = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $addedAt = null;

    #[ORM\ManyToMany(targetEntity: Event::class, mappedBy: 'child')]
    private Collection $events;

    #[ORM\ManyToMany(targetEntity: Carpool::class, mappedBy: 'child')]
    private Collection $carpools;

    public function __construct()
    {
        $this->setAddedAt(new DateTimeImmutable());
        $this->events = new ArrayCollection();
        $this->carpools = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getBirthday(): ?\DateTimeImmutable
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeImmutable $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getIsActive(): ?int
    {
        return $this->isActive;
    }

    public function setIsActive(int $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getAddedAt(): ?\DateTimeImmutable
    {
        return $this->addedAt;
    }

    public function setAddedAt(\DateTimeImmutable $addedAt): self
    {
        $this->addedAt = $addedAt;

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->addChild($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            $event->removeChild($this);
        }

        return $this;
    }

    public function getIdentity(): string
    {
        return $this->lastName . ' ' . $this->firstName;
    }

    /**
     * @return Collection<int, Carpool>
     */
    public function getCarpools(): Collection
    {
        return $this->carpools;
    }

    public function addCarpool(Carpool $carpool): self
    {
        if (!$this->carpools->contains($carpool)) {
            $this->carpools->add($carpool);
            $carpool->addChild($this);
        }

        return $this;
    }

    public function removeCarpool(Carpool $carpool): self
    {
        if ($this->carpools->removeElement($carpool)) {
            $carpool->removeChild($this);
        }

        return $this;
    }

    public function setChildPictureFile(?File $childPictureFile = null): void
    {
        $this->childPictureFile = $childPictureFile;

        if (null !== $childPictureFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getChildPictureFile(): ?File
    {
        return $this->childPictureFile;
    }

    public function setChildPictureName(?string $childPictureName): void
    {
        $this->childPictureName = $childPictureName;
    }

    public function getChildPictureName(): ?string
    {
        return $this->childPictureName;
    }
}
