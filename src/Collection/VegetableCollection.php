<?php declare(strict_types = 1);

namespace App\Collection;

use App\Entity\Produce;
use App\Entity\ProduceType;
use Doctrine\Common\Collections\ArrayCollection;

class VegetableCollection extends ArrayCollection
{
    public function add(mixed $element)
    {
        if (!$element instanceof Produce) {
            throw new \InvalidArgumentException('Element must be an instance of Produce');
        }

        if ($element->getType() !== ProduceType::VEGETABLE) {
            throw new \InvalidArgumentException('Element must be of type '. ProduceType::VEGETABLE);
        }

        parent::add($element);
    }

    public function remove(int|string $key)
    {
        return parent::remove($key);
    }

    public function list(): array
    {
        return parent::toArray();
    }

}
