<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\Range(
     *     max="now year",
     *     min=1850
     * )
     */
    private $manufactured;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $generation;

    /**
     * @ORM\Column(type="string", length=3)
     * @Assert\Choice({"FWD", "RWD", "AWD"})
     */
    private $driven_axle;

    /**
     * @ORM\Column(type="integer", length=2)
     */
    private $seat_count;

    /**
     * @ORM\ManyToOne(targetEntity=Manufacturer::class, inversedBy="cars")
     * @ORM\JoinColumn(nullable=false)
     */
    private $manufacturer;

    /**
     * @ORM\ManyToOne(targetEntity=BodyStyle::class, inversedBy="cars")
     * @ORM\JoinColumn(nullable=false)
     */
    private $body_style;

    /**
     * @ORM\ManyToOne(targetEntity=Engine::class, inversedBy="cars")
     * @ORM\JoinColumn(nullable=false)
     */
    private $engine;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="cars")
     * @ORM\JoinColumn(nullable=false)
     */
    private $added_by;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="car", orphanRemoval=true)
     * @Ignore()
     */
    private $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getManufacturer(): ?Manufacturer
    {
        return $this->manufacturer;
    }

    public function setManufacturer(?Manufacturer $manufacturer): self
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }

    public function getBodyStyle(): ?BodyStyle
    {
        return $this->body_style;
    }

    public function setBodyStyle(?BodyStyle $body_style): self
    {
        $this->body_style = $body_style;

        return $this;
    }

    public function getEngine(): ?Engine
    {
        return $this->engine;
    }

    public function setEngine(?Engine $engine): self
    {
        $this->engine = $engine;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getManufactured()
    {
        return $this->manufactured;
    }

    /**
     * @param mixed $manufactured
     */
    public function setManufactured($manufactured): void
    {
        $this->manufactured = $manufactured;
    }

    public function getAddedBy(): ?User
    {
        return $this->added_by;
    }

    public function setAddedBy(?User $added_by): self
    {
        $this->added_by = $added_by;

        return $this;
    }

    public function __toString(): string
    {
        return $this->manufacturer->getName() . ' ' . $this->getName();
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setCar($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getCar() === $this) {
                $image->setCar(null);
            }
        }

        return $this;
    }
}
