<?php

namespace App\Entity;

use App\Repository\BodyStyleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BodyStyleRepository::class)
 */
class BodyStyle
{
    /**
     * @var $id int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", length=8, options={"unsigned": true})
     */
    private $id;

    /**
     * @var $name string
     * @ORM\Column(type="string", lenght=255)
     */
    private $name;

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
}
