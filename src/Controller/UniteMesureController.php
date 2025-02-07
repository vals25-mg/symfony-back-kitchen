<?php

namespace App\Controller;

use App\Service\UniteMesureService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class UniteMesureController extends AbstractController
{
    private UniteMesureService $uniteMesureService;

    public function __construct(UniteMesureService $uniteMesureService)
    {
        $this->uniteMesureService = $uniteMesureService;
    }

    #[Route('/api/unite-mesure', name: 'create_unite_mesure', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['nom_unite'])) {
            return new JsonResponse(['error' => 'Le nom de l\'unité est requis.'], 400);
        }

        $uniteMesure = $this->uniteMesureService->createUniteMesure($data['nom_unite']);

        return new JsonResponse([
            'id' => $uniteMesure->getIdUniteMesure(),
            'nom_unite' => $uniteMesure->getNomUnite()
        ], 201);
    }

    #[Route('/api/unite-mesure/{id}', name: 'delete_unite_mesure', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        try {
            $this->uniteMesureService->deleteUniteMesure($id);
            return new JsonResponse(['message' => 'Unité de mesure supprimée.']);
        } catch (EntityNotFoundException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 404);
        }
    }

    #[Route('/api/unite-mesure/{id}', name: 'update_unite_mesure', methods: ['PUT'])]
    public function update(Request $request, int $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['nom_unite'])) {
            return new JsonResponse(['error' => 'Le nom de l\'unité est requis.'], 400);
        }

        try {
            $uniteMesure = $this->uniteMesureService->updateUniteMesure($id, $data['nom_unite']);
            return new JsonResponse([
                'id' => $uniteMesure->getIdUniteMesure(),
                'nom_unite' => $uniteMesure->getNomUnite()
            ]);
        } catch (EntityNotFoundException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 404);
        }
    }
}
