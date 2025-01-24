<?php declare(strict_types = 1);

namespace App\Tests\Service;

use App\Entity\Produce;
use App\Entity\ProduceType;
use App\Exception\InvalidProduceDataException;
use App\Processor\ProduceProcessor;
use App\Service\ProduceSorter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProduceSorterTest extends TestCase
{
    private ProduceSorter $produceSorter;
    private MockObject|ProduceProcessor $produceProcessor;

    protected function setUp(): void
    {
        $this->produceProcessor = $this->createMock(ProduceProcessor::class);
        $this->produceSorter = new ProduceSorter($this->produceProcessor);
    }

    public function testProcessRequestWithSingleFruit(): void
    {
        $json = '[{"name":"Apple","type":"fruit", "quantity": 10, "unit": "kg"}]';
        $produceData = ['name' => 'Apple', 'type' => 'fruit', "quantity" => 10, "unit" => "kg"];

        $produce = new Produce();
        $produce->setType((new ProduceType())->setName(ProduceType::FRUIT));

        $this->produceProcessor
            ->expects($this->once())
            ->method('createProduceFromData')
            ->with($produceData)
            ->willReturn($produce);

        $this->produceProcessor
            ->expects($this->exactly(2))
            ->method('saveProduceCollection');

        $this->produceSorter->processRequest($json);

        $this->assertCount(1, $this->produceSorter->getFruits());
        $this->assertCount(0, $this->produceSorter->getVegetables());
    }

    public function testProcessRequestWithSingleVegetable(): void
    {
        $json = '[{"name":"Carrot","type":"vegetable","quantity": 3, "unit": "kg"}]';
        $produceData = ['name' => 'Carrot', 'type' => 'vegetable', "quantity" => 3, "unit" => "kg"];

        $produce = new Produce();
        $produce->setType((new ProduceType())->setName(ProduceType::VEGETABLE));

        $this->produceProcessor
            ->expects($this->once())
            ->method('createProduceFromData')
            ->with($produceData)
            ->willReturn($produce);

        $this->produceProcessor
            ->expects($this->exactly(2))
            ->method('saveProduceCollection');

        $this->produceSorter->processRequest($json);

        $this->assertCount(0, $this->produceSorter->getFruits());
        $this->assertCount(1, $this->produceSorter->getVegetables());
    }

    public function testProcessRequestWithMixedProduce(): void
    {
        $json = '[
            {"name":"Apple","type":"fruit", "quantity": 10, "unit": "kg"},
            {"name":"Carrot","type":"vegetable","quantity": 3, "unit": "kg"}
        ]';

        $fruit = new Produce();
        $fruit->setType((new ProduceType())->setName(ProduceType::FRUIT));

        $vegetable = new Produce();
        $vegetable->setType((new ProduceType())->setName(ProduceType::VEGETABLE));

        $this->produceProcessor
            ->expects($this->exactly(2))
            ->method('createProduceFromData')
            ->willReturnOnConsecutiveCalls($fruit, $vegetable);

        $this->produceProcessor
            ->expects($this->exactly(2))
            ->method('saveProduceCollection');

        $this->produceSorter->processRequest($json);

        $this->assertCount(1, $this->produceSorter->getFruits());
        $this->assertCount(1, $this->produceSorter->getVegetables());
    }

    public function testProcessRequestWithInvalidJson(): void
    {
        $this->expectException(InvalidProduceDataException::class);
        $this->expectExceptionMessage('Invalid JSON format');

        $this->produceSorter->processRequest('{invalid json}');
    }
}