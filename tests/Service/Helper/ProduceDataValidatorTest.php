<?php declare(strict_types = 1);

namespace App\Tests\Service\Helper;

use App\Entity\ProduceType;
use App\Enum\UnitType;
use App\Exception\InvalidProduceDataException;
use App\Service\Helper\MandatoryFieldMissingHelper;
use App\Service\Helper\ProduceDataValidator;
use PHPUnit\Framework\TestCase;

class ProduceDataValidatorTest extends TestCase
{
    public function testValidateProduceDataThrowsExceptionWhenArrayIsEmpty(): void
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('Produce data is empty');

        ProduceDataValidator::validateProduceData([]);
    }

    public function testValidateProduceDataThrowsExceptionWhenNameIsMissing(): void
    {
        self::expectException(InvalidProduceDataException::class);
        self::expectExceptionMessage(MandatoryFieldMissingHelper::message('name'));

        ProduceDataValidator::validateProduceData([
            'quantity' => 10,
            'type' => 'fruit',
            'unit' => 'kg'
        ]);
    }

    public function testValidateProduceDataThrowsExceptionWhenQuantityIsMissing(): void
    {
        self::expectException(InvalidProduceDataException::class);
        self::expectExceptionMessage(MandatoryFieldMissingHelper::message('quantity'));

        ProduceDataValidator::validateProduceData([
            'name' => 'apple',
            'type' => 'fruit',
            'unit' => 'kg'
        ]);
    }

    public function testValidateProduceDataThrowsExceptionWhenTypeIsMissing(): void
    {
        self::expectException(InvalidProduceDataException::class);
        self::expectExceptionMessage(MandatoryFieldMissingHelper::message('type'));

        ProduceDataValidator::validateProduceData([
            'name' => 'apple',
            'quantity' => 10,
            'unit' => 'kg'
        ]);
    }

    public function testValidateProduceDataThrowsExceptionWhenUnitIsMissing(): void
    {
        self::expectException(InvalidProduceDataException::class);
        self::expectExceptionMessage(MandatoryFieldMissingHelper::message('unit'));

        ProduceDataValidator::validateProduceData([
            'name' => 'apple',
            'quantity' => 10,
            'type' => 'fruit'
        ]);
    }

    public function testValidateProduceDataPassesWithValidData(): void
    {
        self::expectNotToPerformAssertions();

        ProduceDataValidator::validateProduceData([
            'name' => 'apple',
            'quantity' => 10,
            'type' => 'fruit',
            'unit' => 'kg'
        ]);
    }

    public function testValidateProduceTypeThrowsExceptionWhenTypeIsEmpty(): void
    {
        $this->expectException(InvalidProduceDataException::class);
        $this->expectExceptionMessage(MandatoryFieldMissingHelper::message('type'));

        ProduceDataValidator::validateProduceType('');
    }

    public function testValidateProduceTypeThrowsExceptionForInvalidType(): void
    {
        $this->expectException(InvalidProduceDataException::class);
        $this->expectExceptionMessage('invalid_type is not a valid produce type. Expected types are: '. ProduceType::FRUIT . ', ' . ProduceType::VEGETABLE);

        ProduceDataValidator::validateProduceType('invalid_type');
    }

    public function testValidateProduceTypePassesForValidTypeFruit(): void
    {
        $this->expectNotToPerformAssertions();

        ProduceDataValidator::validateProduceType(ProduceType::FRUIT);
    }

    public function testValidateProduceTypePassesForValidTypeVegetable(): void
    {
        $this->expectNotToPerformAssertions();

        ProduceDataValidator::validateProduceType(ProduceType::VEGETABLE);
    }

    // Tests for validateProduceQuantity
    public function testValidateProduceQuantityThrowsExceptionWhenQuantityIsZero(): void
    {
        $this->expectException(InvalidProduceDataException::class);
        self::expectExceptionMessage(MandatoryFieldMissingHelper::message('quantity'));

        ProduceDataValidator::validateProduceQuantity(0);
    }

    public function testValidateProduceQuantityThrowsExceptionWhenQuantityIsNegative(): void
    {
        $this->expectException(InvalidProduceDataException::class);
        $this->expectExceptionMessage('Quantity must be positive.');

        ProduceDataValidator::validateProduceQuantity(-5);
    }

    public function testValidateProduceQuantityPassesForPositiveQuantity(): void
    {
        $this->expectNotToPerformAssertions();

        ProduceDataValidator::validateProduceQuantity(10);
    }

    public function testValidateProduceUnitThrowsExceptionWhenUnitIsEmpty(): void
    {
        $this->expectException(InvalidProduceDataException::class);
        $this->expectExceptionMessage(MandatoryFieldMissingHelper::message('unit'));

        ProduceDataValidator::validateProduceUnit('');
    }

    public function testValidateProduceUnitThrowsExceptionForInvalidUnit(): void
    {
        $this->expectException(InvalidProduceDataException::class);
        $this->expectExceptionMessage('invalid_unit is not a valid produce unit. Expected units are ' . UnitType::KILOGRAMM->value . ', ' . UnitType::GRAMM->value);

        ProduceDataValidator::validateProduceUnit('invalid_unit');
    }

    public function testValidateProduceUnitPassesForValidUnitKilogram(): void
    {
        $this->expectNotToPerformAssertions();

        ProduceDataValidator::validateProduceUnit(UnitType::KILOGRAMM->value);
    }

    public function testValidateProduceUnitPassesForValidUnitGram(): void
    {
        $this->expectNotToPerformAssertions();

        ProduceDataValidator::validateProduceUnit(UnitType::GRAMM->value);
    }
}
