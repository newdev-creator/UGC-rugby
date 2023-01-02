<?php

namespace App\Entity;

use App\Repository\EventRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    const MATCH = 1;
    const TOURNAMENT = 2;
    const TRAINING = 3;
    const OTHER = 4;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['event:read'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['event:read'])]
    private ?int $status = null;

    #[ORM\Column(length: 255)]
    #[Groups(['event:read'])]
    private ?string $title = null;

    #[ORM\Column]
    #[Groups(['event:read'])]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(length: 255)]
    #[Groups(['event:read'])]
    private ?string $address = null;

    #[ORM\Column(length: 255)]
    #[Groups(['event:read'])]
    private ?string $postalCode = null;

    #[ORM\Column(length: 255)]
    #[Groups(['event:read'])]
    private ?string $city = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['event:read'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['event:read'])]
    private ?int $nbMinus = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['event:read'])]
    private ?int $nbRegistrant = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $addedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    private ?int $isActive = 1;

    #[ORM\ManyToMany(targetEntity: Category::class, mappedBy: 'event')]
    private Collection $categories;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: Carpool::class)]
    private Collection $carpool;

    #[ORM\ManyToMany(targetEntity: UserChild::class, inversedBy: 'events')]
    private Collection $child;

    public function __construct()
    {
        $this->setAddedAt(new DateTimeImmutable());
        $this->categories = new ArrayCollection();
        $this->carpool = new ArrayCollection();
        $this->child = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

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

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

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

    public function getNbMinus(): ?int
    {
        return $this->nbMinus;
    }

    public function setNbMinus(int $nbMinus): self
    {
        $this->nbMinus = $nbMinus;

        return $this;
    }

    public function getNbRegistrant(): ?int
    {
        return $this->nbRegistrant;
    }

    public function setNbRegistrant(int $nbRegistrant): self
    {
        $this->nbRegistrant = $nbRegistrant;

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

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addEvent($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            $category->removeEvent($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Carpool>
     */
    public function getCarpool(): Collection
    {
        return $this->carpool;
    }

    public function addCarpool(Carpool $carpool): self
    {
        if (!$this->carpool->contains($carpool)) {
            $this->carpool->add($carpool);
            $carpool->setEvent($this);
        }

        return $this;
    }

    public function removeCarpool(Carpool $carpool): self
    {
        if ($this->carpool->removeElement($carpool)) {
            // set the owning side to null (unless already changed)
            if ($carpool->getEvent() === $this) {
                $carpool->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserChild>
     */
    public function getChild(): Collection
    {
        return $this->child;
    }

    public function addChild(UserChild $child): self
    {
        if (!$this->child->contains($child)) {
            $this->child->add($child);
        }

        return $this;
    }

    public function removeChild(UserChild $child): self
    {
        $this->child->removeElement($child);

        return $this;
    }
}
