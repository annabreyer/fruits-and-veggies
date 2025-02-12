<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\ProduceType;
use App\Enum\UnitType;
use App\Exception\InvalidProduceDataException;
use App\Repository\ProduceRepository;
use App\Service\Helper\ProduceDataValidator;
use App\Service\ProduceSearcher;
use App\Service\ProduceSorter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

final class ProduceController extends AbstractController
{
    #[OA\Get(
        summary: 'List produce with optional filters',
        parameters: [
            new OA\Parameter(name: 'type', in: 'query', schema: new OA\Schema(type: 'string', enum: [ProduceType::FRUIT, ProduceType::VEGETABLE])),
            new OA\Parameter(name: 'unit', in: 'query', schema: new OA\Schema(type: 'string', enum: [UnitType::GRAMM->value, UnitType::KILOGRAMM->value])),
        ]
    )]
    #[Route('/api/v1/produce', name: 'app_list_produce', methods: ['GET'])]
    public function list(Request $request, ProduceRepository $produceRepository): JsonResponse
    {
        $query = $request->query->all();

        if (empty($query)) {
            $produce = $produceRepository->findAll();

            return $this->json($produce, Response::HTTP_OK, [], [
                'groups' => ['list_produce'],
            ]);
        }

        if (\array_key_exists('type', $query)) {
            try {
                ProduceDataValidator::validateProduceType($query['type']);
            } catch (InvalidProduceDataException $exception) {
                return $this->json(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
            }
        }

        if (\array_key_exists('unit', $query)) {
            try {
                ProduceDataValidator::validateProduceUnit($query['unit']);
            } catch (InvalidProduceDataException $exception) {
                return $this->json(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
            }
        }

        $types   = empty($query['type']) ? ProduceType::PRODUCE_TYPES : [$query['type']];
        $produce = $produceRepository->getProduceFilteredByType($types);
        if (UnitType::GRAMM->value === $query['unit']) {
            return $this->json($produce, Response::HTTP_OK, [], [
                'groups' => ['list_produce'],
            ]);
        }

        return $this->json($produce, Response::HTTP_OK, [], [
            'groups' => ['list_produce_kg'],
        ]);
    }

    #[OA\RequestBody(
        content: new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                type: 'array',
                items: new OA\Items(
                    required: ['name', 'type', 'quantity', 'unit'],
                    properties: [
                        new OA\Property(property: 'name', type: 'string', example: 'Carrot'),
                        new OA\Property(property: 'type', type: 'string', example: 'vegetable'),
                        new OA\Property(property: 'quantity', type: 'number', example: 10922),
                        new OA\Property(property: 'unit', type: 'string', example: 'g'),
                    ]
                )
            )
        )
    )]
    #[Route('/api/v1/produce/add', name: 'app_add_produce', methods: ['POST'])]
    public function add(Request $request, ProduceSorter $produceSorter): JsonResponse
    {
        $jsonData = $request->getContent();

        try {
            $produceSorter->processRequest($jsonData);
        } catch (InvalidProduceDataException $exception) {
            return $this->json(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $this->json(['message' => 'Produce added successfully'], Response::HTTP_CREATED);
    }

    #[OA\Get(
        summary: 'Search produce',
        parameters: [
            new OA\Parameter(name: 'type', in: 'query', schema: new OA\Schema(type: 'string', enum: [ProduceType::FRUIT, ProduceType::VEGETABLE])),
            new OA\Parameter(name: 'name', in: 'query', schema: new OA\Schema(type: 'string')),
        ]
    )]
    #[Route('/api/v1/produce/search', name: 'app_list_search', methods: ['GET'])]
    public function search(Request $request, ProduceSearcher $produceSearcher): JsonResponse
    {
        $query = $request->query->all();

        try {
            $produce = $produceSearcher->searchProduce($query);
        } catch (InvalidProduceDataException $exception) {
            return $this->json(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($produce, Response::HTTP_OK, [], [
            'groups' => ['list_produce'],
        ]);
    }
}
