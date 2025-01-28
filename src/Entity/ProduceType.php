<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProduceTypeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduceTypeRepository::class)]
class ProduceType
{
    public const FRUIT     = 'fruit';
    public const VEGETABLE = 'vegetable';

    public const PRODUCE_TYPES = [
        self::FRUIT     => self::FRUIT,
        self::VEGETABLE => self::VEGETABLE,
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private string $name;

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
}
