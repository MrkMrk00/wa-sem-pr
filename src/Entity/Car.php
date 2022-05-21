<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CarRepository::class)
 */
class Car
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", length=128, options={"unsigned": true})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", length=4)
     */
    private $manufactured_from;

    /**
     * @ORM\Column(type="integer", length=4)
     */
    private $manufactured_until;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $generation;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $driven_axle;

    /**
     * @ORM\Column(type="integer", length=2)
     */
    private $seat_count;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\BodyStyle")
     */
    private $body_styles;

    /**
     * @ORM\ManyToOne(targetEntity=Manufacturer::class, inversedBy="cars")
     * @ORM\JoinColumn(nullable=false)
     */
    private $manufacturer;

    public function __construct()
    {
        $this->body_styles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getManufacturedFrom(): ?int
    {
        return $this->manufactured_from;
    }

    public function setManufacturedFrom(int $manufactured_from): self
    {
        $this->manufactured_from = $manufactured_from;

        return $this;
    }

    public function getManufacturedUntil(): ?int
    {
        return $this->manufactured_until;
    }

    public function setManufacturedUntil(int $manufactured_until): self
    {
        $this->manufactured_until = $manufactured_until;

        return $this;
    }

    public function getGeneration(): ?string
    {
        return $this->generation;
    }

    public function setGeneration(?string $generation): self
    {
        $this->generation = $generation;

        return $this;
    }

    public function getDrivenAxle(): ?string
    {
        return $this->driven_axle;
    }

    public function setDrivenAxle(string $driven_axle): self
    {
        $this->driven_axle = $driven_axle;

        return $this;
    }

    public function getSeatCount(): ?int
    {
        return $this->seat_count;
    }

    public function setSeatCount(int $seat_count): self
    {
        $this->seat_count = $seat_count;

        return $this;
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

    public function getBodyStyles(): Collection
    {
        return $this->body_styles;
    }

    public function getManufacturer(): ?Manufacturer
    {
        return $this->manufacturer;
    }

    public function setManufacturer(?Manufacturer $manufacturer): self
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }
}
