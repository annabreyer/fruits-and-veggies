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
                     ->innerJoin(ProduceType::class, 'pt')
                     ->andWhere('pt.name IN (:produceType)')
                     ->setParameter('produceType', $produceTypeNames)
                     ->getQuery()->getResult();
    }

    /**
     * @return array <int, Produce>
     */
    public function searchProduce(?string $name, string|ProduceType|null $type): array
    {
        $qb = $this->createQueryBuilder('p')
                   ->innerJoin(ProduceType::class, 'pt');

        if (false === empty($name)) {
            $qb->andWhere('p.name LIKE :name')
               ->setParameter('name', '%' . $name . '%');
        }

        if (null !== $type) {
            if ($type instanceof ProduceType) {
                $type = $type->getName();
            }
            $qb->andWhere('pt.name = :type')
               ->setParameter('type', $type);
        }

        return $qb->getQuery()->getResult();
    }
}
