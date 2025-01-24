<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProduceTypeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ProduceTypeRepository::class)]
class ProduceType
{
    public const FRUIT     = 'fruit';
    public const VEGETABLE = 'vegetable';


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['list_produce'])]
    #[ORM\Column(length: 100)]
    private string $name;

    /**
     * @return string[]
     */
    public static function getProduceTypes(): array
    {
        return [
            self::FRUIT     => self::FRUIT,
            self::VEGETABLE => self::VEGETABLE,
        ];
    }

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
