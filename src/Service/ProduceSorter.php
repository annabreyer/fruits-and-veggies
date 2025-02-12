<?php

declare(strict_types=1);

namespace App\Service;

use App\Collection\FruitCollection;
use App\Collection\VegetableCollection;
use App\Entity\Produce;
use App\Entity\ProduceType;
use App\Exception\InvalidProduceDataException;
use App\Processor\ProduceProcessor;
use App\Service\Helper\ProduceDataValidator;
use Doctrine\Common\Collections\ArrayCollection;

class ProduceSorter
{
    /**
     * @var FruitCollection
     */
    private FruitCollection $fruits;

    /**
     * @var VegetableCollection
     */
    private VegetableCollection $vegetables;

    public function __construct(private readonly ProduceProcessor $produceProcessor)
    {
        $this->fruits     = new FruitCollection();
        $this->vegetables = new VegetableCollection();
    }

    /**
     * @throws InvalidProduceDataException
     */
    public function processRequest(string $produceJson): void
    {
        $data = json_decode($produceJson, true);

        if (null === $data) {
            throw new InvalidProduceDataException('Invalid JSON format');
        }

        foreach ($data as $produceData) {
            ProduceDataValidator::validateProduceData($produceData);
            $produce = $this->produceProcessor->createProduceFromData($produceData);
            $this->addProduceToCollection($produce);
        }

        $this->saveProduceCollections();
    }

    /**
     * @return ArrayCollection <int, Produce>
     */
    public function getFruits(): ArrayCollection
    {
        return $this->fruits;
    }

    /**
     * @return ArrayCollection <int, Produce>
     */
    public function getVegetables(): ArrayCollection
    {
        return $this->vegetables;
    }

    private function addProduceToCollection(Produce $produce): void
    {
        if (ProduceType::FRUIT === $produce->getType()->getName()) {
            $this->fruits->add($produce);
        }

        if (ProduceType::VEGETABLE === $produce->getType()->getName()) {
            $this->vegetables->add($produce);
        }
    }

    private function saveProduceCollections(): void
    {
        $this->produceProcessor->saveProduceCollection($this->fruits);
        $this->produceProcessor->saveProduceCollection($this->vegetables);
    }
}
