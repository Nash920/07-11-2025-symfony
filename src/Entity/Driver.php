<?php

namespace App\Entity;

use App\Repository\DriverRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DriverRepository::class)]
class Driver
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 40)]
    private ?string $firstName = null;

    #[ORM\Column(length: 40)]
    private ?string $lastName = null;

    #[ORM\Column]
    private ?int $licensePoints = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $startedAt = null;

    #[ORM\Column(length: 20)]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'drivers')]
    private ?Team $team = null;

    #[ORM\ManyToOne(inversedBy: 'driver')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Team $teams = null;

    /**
     * @var Collection<int, Infraction>
     */
    #[ORM\OneToMany(targetEntity: Infraction::class, mappedBy: 'driver')]
    private Collection $infractions;

    public function __construct()
    {
        $this->infractions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getLicensePoints(): ?int
    {
        return $this->licensePoints;
    }

    public function setLicensePoints(int $licensePoints): static
    {
        $this->licensePoints = $licensePoints;

        return $this;
    }

    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function setStartedAt(\DateTimeImmutable $startedAt): static
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): static
    {
        $this->team = $team;

        return $this;
    }

    public function getTeams(): ?Team
    {
        return $this->teams;
    }

    public function setTeams(?Team $teams): static
    {
        $this->teams = $teams;

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
            $infraction->setDriver($this);
        }

        return $this;
    }

    public function removeInfraction(Infraction $infraction): static
    {
        if ($this->infractions->removeElement($infraction)) {
            // set the owning side to null (unless already changed)
            if ($infraction->getDriver() === $this) {
                $infraction->setDriver(null);
            }
        }

        return $this;
    }
}
