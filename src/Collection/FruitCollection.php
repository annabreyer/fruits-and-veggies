<?php

declare(strict_types=1);

namespace App\Collection;

use App\Entity\Produce;
use App\Entity\ProduceType;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @extends ArrayCollection<int, Produce>
 */
class FruitCollection extends ArrayCollection
{
    public function add(mixed $element)
    {
        if (false === $element instanceof Produce) {
            throw new \InvalidArgumentException('Element must be an instance of Produce');
        }

        if (ProduceType::FRUIT !== $element->getProduceTypeName()) {
            throw new \InvalidArgumentException('Element must be of type ProduceType::FRUIT');
        }

        parent::add($element);
    }

    public function remove(int|string $key)
    {
        return parent::remove($key);
    }

    /**
     * @return array<int, mixed>
     */
    public function list(): array
    {
        return parent::toArray();
    }
}
