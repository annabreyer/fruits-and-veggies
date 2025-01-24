<?php declare(strict_types = 1);

namespace App\Service;

use App\Entity\Produce;
use App\Entity\ProduceType;
use App\Exception\InvalidProduceDataException;
use App\Processor\ProduceProcessor;
use App\Service\Helper\ProduceDataValidator;
use Doctrine\Common\Collections\ArrayCollection;

class ProduceSorter
{
    private ArrayCollection $fruits;
    private ArrayCollection $vegetables;

    public function __construct(private readonly ProduceProcessor $produceProcessor)
    {
        $this->fruits = new ArrayCollection();
        $this->vegetables = new ArrayCollection();
    }

    public function processRequest(string $produceJson): void {
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

    public function getFruits(): ArrayCollection
    {
        return $this->fruits;
    }

    public function getVegetables(): ArrayCollection
    {
        return $this->vegetables;
    }
    private function addProduceToCollection(Produce $produce)
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
