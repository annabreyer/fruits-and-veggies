<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Produce;
use App\Entity\ProduceType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produce>
 */
class ProduceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produce::class);
    }

    /**
     * @param array <string, string> $produceTypeNames
     *
     * @return array <int, Produce>
     */
    public function getProduceFilteredByType(array $produceTypeNames): array
    {
        return $this->createQueryBuilder('p')
                     ->innerJoin(ProduceType::class, 'pt', 'WITH', 'p.type = pt.id')
                     ->andWhere('pt.name IN (:produceType)')
                     ->setParameter('produceType', $produceTypeNames)
                     ->getQuery()->getResult();
    }
}
