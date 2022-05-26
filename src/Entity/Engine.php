<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\EngineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EngineRepository::class)
 */
class Engine
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(
     *     type="integer",
     *     length=128,
     *     options={"unsigned": true})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $size;

    /**
     * @ORM\Column(type="integer", length=2)
     */
    private $number_of_cylinders;

    /**
     * @ORM\Column(type="integer")
     */
    private $max_power;

    /**
     * @ORM\Column(type="integer")
     */
    private $max_torque;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $aspiration;

    /**
     * @ORM\ManyToMany(targetEntity=Car::class, mappedBy="engines")
     */
    private $cars;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $fuel;

    public function __construct()
    {
        $this->cars = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getNumberOfCylinders(): ?int
    {
        return $this->number_of_cylinders;
    }

    public function setNumberOfCylinders(int $number_of_cylinders): self
    {
        $this->number_of_cylinders = $number_of_cylinders;

        return $this;
    }

    public function getMaxPower(): ?int
    {
        return $this->max_power;
    }

    public function setMaxPower(int $max_power): self
    {
        $this->max_power = $max_power;

        return $this;
    }

    public function getMaxTorque(): ?int
    {
        return $this->max_torque;
    }

    public function setMaxTorque(int $max_torque): self
    {
        $this->max_torque = $max_torque;

        return $this;
    }

    public function getAspiration(): ?string
    {
        return $this->aspiration;
    }

    public function setAspiration(string $aspiration): self
    {
        $this->aspiration = $aspiration;

        return $this;
    }

    /**
     * @return Collection<int, Car>
     */
    public function getCars(): Collection
    {
        return $this->cars;
    }

    public function addCar(Car $car): self
    {
        if (!$this->cars->contains($car)) {
            $this->cars[] = $car;
            $car->addEngine($this);
        }

        return $this;
    }

    public function removeCar(Car $car): self
    {
        if ($this->cars->removeElement($car)) {
            $car->removeEngine($this);
        }

        return $this;
    }

    public function __toString()
    {
        return ($this->name ? $this->name . ' ' : '') . $this->fuel . ' ' . $this->size . 'cm³ ' . $this->number_of_cylinders . 'piston';
    }

    public function getFuel(): ?string
    {
        return $this->fuel;
    }

    public function setFuel(string $fuel): self
    {
        $this->fuel = $fuel;

        return $this;
    }


}
