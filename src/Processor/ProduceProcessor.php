<?php declare(strict_types = 1);

namespace App\Processor;

use App\Entity\Produce;
use App\Enum\UnitType;
use App\Repository\ProduceTypeRepository;
use App\Service\Helper\ProduceDataValidator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class ProduceProcessor
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ProduceTypeRepository $produceTypeRepository
    ) {

    }

    public function createProduceFromData(array $data): Produce
    {
        ProduceDataValidator::validateProduceData($data);

        $weightInGrams = $data['quantity'];

        if (UnitType::GRAMM->value !== $data['unit'] && UnitType::KILOGRAMM->value === $data['unit']) {
            $weightInGrams = $this->convertKilogrammToGram($data['quantity']);
        }

        $produce = $this->createProduce($data['name'], $weightInGrams, $data['type']);

        return $produce;
    }

    public function createProduce(string $name, int $weightInGrams, string $type): Produce
    {
        $produceType = $this->produceTypeRepository->findOneBy(['name' => $type]);
        if (null === $produceType) {
            throw new \InvalidArgumentException(sprintf('ProduceType with name %s does not exist.', $type));
        }

        $produce = new Produce();
        $produce->setName($name)
                ->setWeight($weightInGrams)
                ->setType($produceType)
        ;

        return $produce;
    }

    public function convertKilogrammToGram(int $quantity): int
    {
        return $quantity * 1000;
    }

    public function saveProduceCollection(ArrayCollection $produces): void
    {
        foreach ($produces as $produce) {
            $this->entityManager->persist($produce);
        }

        $this->entityManager->flush();
    }
}
