<?php

namespace App\Entity;

use App\Repository\CarReviewRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CarReviewRepository::class)
 */
class CarReview
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", length=128, options={"unsigned": true})
     */
    private $id;

    /**
     * @ORM\Column(type="integer", length=2)
     * @Assert\Choice({-1, 0, 1})
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity=Car::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $car;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getCar(): ?Car
    {
        return $this->car;
    }

    public function setCar(?Car $car): self
    {
        $this->car = $car;

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
}
