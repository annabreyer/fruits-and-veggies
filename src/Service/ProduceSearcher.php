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

        $name = null;
        $type = null;

        if (\array_key_exists('type', $query)) {
            ProduceDataValidator::validateProduceType($query['type']);
            $type = $query['type'];
        }

        if (\array_key_exists('name', $query)) {
            $name = $query['name'];
        }

        return $this->produceRepository->searchProduce($name, $type);
    }
}
