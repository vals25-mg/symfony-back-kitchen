<?php

namespace App\Controller;

use App\Service\StockIngredientService;
use App\Repository\StockIngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\FirebaseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class StockIngredientController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private StockIngredientService $ingredientService;
    private StockIngredientRepository $ingredientRepository;
    private FirebaseService $firebaseService;


    public function __construct(StockIngredientService $stockIngredientService, StockIngredientRepository $stockIngredientRepository, EntityManagerInterface $entityManager, FirebaseService $firebaseService)
    {
        $this->stockIngredientService = $stockIngredientService;
        $this->stockIngredientRepository = $stockIngredientRepository;
        $this->entityManager = $entityManager;
        $this->firebaseService = $firebaseService;
    }

    /*#[Route('/api/v1/add/stock_ingredients', name: 'add_stock_ingredient', methods: ['POST'])]
    public function addStockIngredient(Request $request, SerializerInterface $serializer): JsonResponse
    {
        $idIngredient = $request->request->get('id_ingredient');
        $valeurEntree = $request->request->get('entree');

        if (!$idIngredient || !$valeurEntree) {
            return $this->json(['error' => 'Tout les champs sont requis!'], 400);
        }

        try {

            $stockIngredient = $this->stockIngredientService->addStockIngredient($idIngredient, $valeurEntree);
            $jsonContent = $serializer->serialize($ingredient, 'json', ['groups' => ['stockIngredient:read']]);
            return new JsonResponse($jsonContent, 201, [], true);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }*/

    #[Route('/api/v1/add/stock_ingredients', name: 'add_stock', methods: ['POST'])]
    public function addStock(Request $request, SerializerInterface $serializer): JsonResponse
    {
        $token = $request->headers->get('Authorization');
        
        if (!$token || !str_starts_with($token, 'Bearer ')) {
            return $this->json(['error' => 'Token introuvable ou invalide!'], 401);
        }

        try {
            $firebaseToken = str_replace('Bearer ', '', $token);

            $decodedToken = $this->firebaseService->verifyIdToken($firebaseToken);

        } catch (\Exception $e) {
            return $this->json(['error' => 'Utilisateur non connecte: ' . $e->getMessage()], 401);
        }

        date_default_timezone_set('Africa/Nairobi');

        $data = json_decode($request->getContent(), true);

        if (!isset($data['id_ingredient']) || !isset($data['valeur_entree'])) {
            return new JsonResponse(['error' => 'id_ingredient and valeur_entree are required'], 400);
        }

        try {
            $stockIngredient = $this->stockIngredientService->addStockIngredient(
                $data['id_ingredient'],
                $data['valeur_entree']
            );

            $jsonContent = $serializer->serialize($stockIngredient, 'json', ['groups' => 'stockIngredient:read']);
            return new JsonResponse([
                'id_stock_ingredient' => $stockIngredient->getId(),
                'ingredient' => [
                    'id_ingredient'=> $stockIngredient->getIdIngredient()->getId(),
                    'nom_ingredient' => $stockIngredient->getIdIngredient()->getNomIngredient(),
                ], 
                'valeur_entree' => $stockIngredient->getEntree(),
                'valeur_sortie' => $stockIngredient->getSortie(),
            ], 201);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }


    #[Route('/api/v1/update_stock_ingredients/{id}', name: 'update_stock', methods: ['PUT'])]
    public function updateStock(int $id, Request $request, SerializerInterface $serializer): JsonResponse
    {

        $token = $request->headers->get('Authorization');
        
        if (!$token || !str_starts_with($token, 'Bearer ')) {
            return $this->json(['error' => 'Token introuvable ou invalide!'], 401);
        }

        try {
            $firebaseToken = str_replace('Bearer ', '', $token);

            $decodedToken = $this->firebaseService->verifyIdToken($firebaseToken);

        } catch (\Exception $e) {
            return $this->json(['error' => 'Utilisateur non connecte: ' . $e->getMessage()], 401);
        }

        $data = json_decode($request->getContent(), true);

        try {
            $stockIngredient = $this->stockIngredientService->updateStockIngredient($id, $data);

            $jsonContent = $serializer->serialize($stockIngredient, 'json', ['groups' => 'stockIngredient:read']);
            return new JsonResponse([
                'id_stock_ingredient' => $stockIngredient->getId(),
                'ingredient' => [
                    'id_ingredient'=> $stockIngredient->getIdIngredient()->getId(),
                    'nom_ingredient' => $stockIngredient->getIdIngredient()->getNomIngredient(),
                ], 
                'valeur_entree' => $stockIngredient->getEntree(),
                'valeur_sortie' => $stockIngredient->getSortie(),
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }



    #[Route('api/v1/get_list_stock_ingredient', name: 'get_stock_ingredients', methods: ['GET'])]
    public function listStockIngredients(): JsonResponse
    {
        // Retrieve all ingredients from the repository
        $stockIngredients = $this->stockIngredientRepository->findAllStockIngredient();

        // Convert ingredients to an array (or any structure that suits you)
        $data = [];
        foreach ($stockIngredients as $stockIngredient) {
            $data[] = [
                'id' => $stockIngredient->getId(),
                'ingredient' => [
                    'id' => $stockIngredient->getIdIngredient()->getIdIngredient(),
                    'nomIngredient' => $stockIngredient->getIdIngredient()->getNomIngredient(),
                    'nomUnite' => $stockIngredient->getIdIngredient()->getIdUniteMesure()->getNomUnite()
                ],
                'valeur_entree' => $stockIngredient->getEntree(),
                'valeur_sortie' => $stockIngredient->getSortie(),
                'date_entree' => $stockIngredient->getDateMouvement()
            ];
        }

        // Return the ingredients as JSON
        return new JsonResponse($data);
    }
}
