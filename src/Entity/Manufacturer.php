<?php

namespace App\Entity;

use App\Repository\ManufacturerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ManufacturerRepository::class)
 */
class Manufacturer
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(
     *     type="integer",
     *     length=8,
     *     options={"unsigned": true})
     */
    private $id;

    /**
     * @ORM\Column(
     *     type="string",
     *     length=255,
     *     unique=true)
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min=1, max=20)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Car::class, mappedBy="manufacturer")
     * @Ignore()
     */
    private $cars;

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

    public function setName(string $name): self
    {
        $this->name = $name;

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
            $car->setManufacturer($this);
        }

        return $this;
    }

    public function removeCar(Car $car): self
    {
        if ($this->cars->removeElement($car)) {
            // set the owning side to null (unless already changed)
            if ($car->getManufacturer() === $this) {
                $car->setManufacturer(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
