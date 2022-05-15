<?php

namespace App\Entity;

use App\Repository\ManufacturerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ManufacturerRepository::class)
 */
class Manufacturer
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
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
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Car", mappedBy="manufacturer")
     * @ORM\Column(type="string")
     */
    private $cars;

    public function __construct() {
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
     * @return Collection|Car[]
     */
    public function getCars(): Collection
    {
        return $this->cars;
    }

}
