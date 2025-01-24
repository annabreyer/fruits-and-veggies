<?php

namespace App\Entity;

use App\Repository\ProduceTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduceTypeRepository::class)]
class ProduceType
{

    public CONST FRUIT = 'fruit';
    public CONST VEGETABLE = 'vegetable';


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    /**
     * @var Collection<int, Produce>
     */
    #[ORM\OneToMany(targetEntity: Produce::class, mappedBy: 'type', orphanRemoval: true)]
    private Collection $produces;

    public static function getProduceTypes(): array
    {
        return [
            self::FRUIT     => self::FRUIT,
            self::VEGETABLE => self::VEGETABLE,
        ];
    }

    public function __construct()
    {
        $this->produces = new ArrayCollection();
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

    /**
     * @return Collection<int, Produce>
     */
    public function getProduces(): Collection
    {
        return $this->produces;
    }

    public function addProduce(Produce $produce): static
    {
        if (!$this->produces->contains($produce)) {
            $this->produces->add($produce);
            $produce->setType($this);
        }

        return $this;
    }

    public function removeProduce(Produce $produce): static
    {
        if ($this->produces->removeElement($produce)) {
            // set the owning side to null (unless already changed)
            if ($produce->getType() === $this) {
                $produce->setType(null);
            }
        }

        return $this;
    }
}
