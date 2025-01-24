<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProduceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\SerializedName;

#[ORM\Entity(repositoryClass: ProduceRepository::class)]
class Produce
{
    #[Groups(['list_produce', 'list_produce_kg'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['list_produce', 'list_produce_kg'])]
    #[ORM\Column(length: 100)]
    private string $name;

    #[ORM\Column]
    private int $weight;

    #[ORM\ManyToOne(inversedBy: 'produces')]
    #[ORM\JoinColumn(nullable: false)]
    private ProduceType $type;

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

    #[SerializedName('quantity')]
    #[Groups(['list_produce'])]
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

    #[Groups(['list_produce', 'list_produce_kg'])]
    #[SerializedName('type')]
    public function getProduceTypeName(): string
    {
        return $this->type->getName();
    }

    #[SerializedName('quantity')]
    #[Groups(['list_produce_kg'])]
    public function getWeightInKg(): float
    {
        return round($this->weight / 1000);
    }
}
