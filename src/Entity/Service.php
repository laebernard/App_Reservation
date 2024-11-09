<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    /**
     * @var Collection<int, Booking>
     */
    #[ORM\ManyToMany(targetEntity: Booking::class, mappedBy: 'serviceId')]
    private Collection $bookings;

    /**
     * @var Collection<int, Booking>
     */
    #[ORM\OneToMany(targetEntity: Booking::class, mappedBy: 'serviceId')]
    private Collection $bookingsService;

    public function __construct()
    {
        $this->bookings = new ArrayCollection();
        $this->bookingsService = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Booking>
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): static
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings->add($booking);
            $booking->addServiceId($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): static
    {
        if ($this->bookings->removeElement($booking)) {
            $booking->removeServiceId($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Booking>
     */
    public function getBookingsService(): Collection
    {
        return $this->bookingsService;
    }

    public function addBookingsService(Booking $bookingsService): static
    {
        if (!$this->bookingsService->contains($bookingsService)) {
            $this->bookingsService->add($bookingsService);
            $bookingsService->setServiceId($this);
        }

        return $this;
    }

    public function removeBookingsService(Booking $bookingsService): static
    {
        if ($this->bookingsService->removeElement($bookingsService)) {
            // set the owning side to null (unless already changed)
            if ($bookingsService->getServiceId() === $this) {
                $bookingsService->setServiceId(null);
            }
        }

        return $this;
    }
}
