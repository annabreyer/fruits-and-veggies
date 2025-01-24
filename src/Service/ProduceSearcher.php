<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\InvalidProduceDataException;
use App\Repository\ProduceRepository;
use App\Service\Helper\ProduceDataValidator;
use App\Entity\Produce;

class ProduceSearcher
{
    public function __construct(private readonly ProduceRepository $produceRepository)
    {

    }

    /**
     * @param array <string, mixed> $query
     *
     * @return array <int, Produce>
     *
     * @throws InvalidProduceDataException
     */
    public function searchProduce(array $query): array
    {
        if (empty($query)) {
            return [];
        }

        if (\array_key_exists('type', $query)) {
            ProduceDataValidator::validateProduceType($query['type']);
        }

        $produce = $this->produceRepository->findAll();

        return array_filter($produce, static function (Produce $produce) use ($query) {
            $searchLower = strtolower($query['name']);
            $nameLower   = strtolower($produce->getName());
            $typeLower   = strtolower($produce->getProduceTypeName());

            $nameWords   = explode(' ', $nameLower);
            $typeWords   = explode(' ', $typeLower);
            $searchWords = explode(' ', $searchLower);

            return \count(array_intersect($nameWords, $searchWords)) > 0
                || \count(array_intersect($typeWords, $searchWords)) > 0;
        });
    }
}
