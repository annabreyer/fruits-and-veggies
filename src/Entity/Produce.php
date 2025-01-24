<?php

namespace App\Entity;

use App\Repository\ProduceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduceRepository::class)]
class Produce
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $weight = null;

    #[ORM\ManyToOne(inversedBy: 'produces')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProduceType $type = null;

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

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    public function getType(): ?ProduceType
    {
        return $this->type;
    }

    public function setType(?ProduceType $type): static
    {
        $this->type = $type;

        return $this;
    }
}
