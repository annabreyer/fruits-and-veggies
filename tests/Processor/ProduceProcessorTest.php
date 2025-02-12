<?php

declare(strict_types=1);

namespace App\Tests\Processor;

use App\Entity\Produce;
use App\Entity\ProduceType;
use App\Enum\UnitType;
use App\Processor\ProduceProcessor;
use App\Repository\ProduceTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class ProduceProcessorTest extends TestCase
{
    private EntityManagerInterface $entityManager;
    private ProduceTypeRepository $produceTypeRepository;
    private ProduceProcessor $produceProcessor;

    protected function setUp(): void
    {
        $this->entityManager         = $this->createMock(EntityManagerInterface::class);
        $this->produceTypeRepository = $this->createMock(ProduceTypeRepository::class);

        $this->produceProcessor = new ProduceProcessor(
            $this->entityManager,
            $this->produceTypeRepository
        );
    }

    public function testCreateProduceFromDataValidatesData(): void
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('Produce data is empty.');

        $this->produceProcessor->createProduceFromData([]);
    }

    public function testCreateProduceFromDataConvertsQuantityToGramsIfUnitIsNotGram(): void
    {
        $data = [
            'name'     => 'apple',
            'quantity' => 2,
            'type'     => ProduceType::FRUIT,
            'unit'     => UnitType::KILOGRAMM->value,
        ];

        $fruitType = new ProduceType();
        $fruitType->setName(ProduceType::FRUIT);

        $this->produceTypeRepository->method('findOneBy')
            ->willReturn($fruitType);

        $produce = $this->produceProcessor->createProduceFromData($data);

        self::assertInstanceOf(Produce::class, $produce);
        self::assertSame(2000, $produce->getWeight());
    }

    public function testCreateProduceFromDataCreatesProduce(): void
    {
        $data = [
            'name'     => 'apple',
            'quantity' => 2,
            'type'     => ProduceType::FRUIT,
            'unit'     => UnitType::GRAMM->value,
        ];

        $fruitType = new ProduceType();
        $fruitType->setName(ProduceType::FRUIT);

        $this->produceTypeRepository->method('findOneBy')
            ->willReturn($fruitType);

        $produce = $this->produceProcessor->createProduceFromData($data);

        self::assertInstanceOf(Produce::class, $produce);
        self::assertSame($data['quantity'], $produce->getWeight());
        self::assertSame($data['name'], $produce->getName());
    }

    public function testCreateProduceThrowsExceptionOfTypeDoesNotExistInDatabase(): void
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('ProduceType with name meat does not exist.');

        $this->produceProcessor->createProduce('Steak', 250, 'meat');
    }
}
