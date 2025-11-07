<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
class Team
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 80)]
    private ?string $name = null;

    /**
     * @var Collection<int, Driver>
     */
    #[ORM\OneToMany(targetEntity: Driver::class, mappedBy: 'team')]
    private Collection $drivers;

    /**
     * @var Collection<int, Driver>
     */
    #[ORM\OneToMany(targetEntity: Driver::class, mappedBy: 'teams')]
    private Collection $driver;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Engine $engine = null;

    /**
     * @var Collection<int, Infraction>
     */
    #[ORM\OneToMany(targetEntity: Infraction::class, mappedBy: 'team')]
    private Collection $infractions;

    public function __construct()
    {
        $this->drivers = new ArrayCollection();
        $this->driver = new ArrayCollection();
        $this->infractions = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Driver>
     */
    public function getDrivers(): Collection
    {
        return $this->drivers;
    }

    public function addDriver(Driver $driver): static
    {
        if (!$this->drivers->contains($driver)) {
            $this->drivers->add($driver);
            $driver->setTeam($this);
        }

        return $this;
    }

    public function removeDriver(Driver $driver): static
    {
        if ($this->drivers->removeElement($driver)) {
            // set the owning side to null (unless already changed)
            if ($driver->getTeam() === $this) {
                $driver->setTeam(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Driver>
     */
    public function getDriver(): Collection
    {
        return $this->driver;
    }

    public function getEngine(): ?Engine
    {
        return $this->engine;
    }

    public function setEngine(Engine $engine): static
    {
        $this->engine = $engine;

        return $this;
    }

    /**
     * @return Collection<int, Infraction>
     */
    public function getInfractions(): Collection
    {
        return $this->infractions;
    }

    public function addInfraction(Infraction $infraction): static
    {
        if (!$this->infractions->contains($infraction)) {
            $this->infractions->add($infraction);
            $infraction->setTeam($this);
        }

        return $this;
    }

    public function removeInfraction(Infraction $infraction): static
    {
        if ($this->infractions->removeElement($infraction)) {
            // set the owning side to null (unless already changed)
            if ($infraction->getTeam() === $this) {
                $infraction->setTeam(null);
            }
        }

        return $this;
    }
}
