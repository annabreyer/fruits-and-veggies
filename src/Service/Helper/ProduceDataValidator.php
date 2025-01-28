<?php

declare(strict_types=1);

namespace App\Service\Helper;

use App\Entity\ProduceType;
use App\Enum\UnitType;
use App\Exception\InvalidProduceDataException;

class ProduceDataValidator
{
    /**
     * @param array <string, mixed> $produceData
     *
     * @throws InvalidProduceDataException
     * @throws \InvalidArgumentException
     */
    public static function validateProduceData(array $produceData): void
    {
        if (empty($produceData)) {
            throw new \InvalidArgumentException('Produce data is empty.');
        }

        if (empty($produceData['name'])) {
            throw new InvalidProduceDataException(MandatoryFieldMissingHelper::message('name'));
        }

        if (empty($produceData['quantity'])) {
            throw new InvalidProduceDataException(MandatoryFieldMissingHelper::message('quantity'));
        }

        if (empty($produceData['type'])) {
            throw new InvalidProduceDataException(MandatoryFieldMissingHelper::message('type'));
        }

        if (empty($produceData['unit'])) {
            throw new InvalidProduceDataException(MandatoryFieldMissingHelper::message('unit'));
        }

        self::validateProduceQuantity($produceData['quantity']);
        self::validateProduceType($produceData['type']);
        self::validateProduceUnit($produceData['unit']);
    }

    /**
     * @throws InvalidProduceDataException
     */
    public static function validateProduceType(string $type): void
    {
        if (empty($type)) {
            throw new InvalidProduceDataException(MandatoryFieldMissingHelper::message('type'));
        }

        if (false === \in_array($type, ProduceType::PRODUCE_TYPES, true)) {
            throw new InvalidProduceDataException(\sprintf('%s is not a valid produce type. Expected types are: %s.', $type, implode(', ', ProduceType::PRODUCE_TYPES)));
        }
    }

    /**
     * @throws InvalidProduceDataException
     */
    public static function validateProduceQuantity(int $quantity): void
    {
        if (empty($quantity)) {
            throw new InvalidProduceDataException(MandatoryFieldMissingHelper::message('quantity'));
        }

        if ($quantity <= 0) {
            throw new InvalidProduceDataException('Quantity must be positive.');
        }
    }

    /**
     * @throws InvalidProduceDataException
     */
    public static function validateProduceUnit(string $unit): void
    {
        if (empty($unit)) {
            throw new InvalidProduceDataException(MandatoryFieldMissingHelper::message('unit'));
        }

        if ($unit !== UnitType::KILOGRAMM->value && $unit !== UnitType::GRAMM->value) {
            throw new InvalidProduceDataException(\sprintf('%s is not a valid produce unit. Expected units are %s.', $unit, UnitType::KILOGRAMM->value . ', ' . UnitType::GRAMM->value));
        }
    }
}
