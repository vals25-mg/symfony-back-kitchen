<?php

namespace App\Controller;

use App\Repository\IngredientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class IngredientController extends AbstractController
{
    #[Route('/api/ingredients', name: 'api_ingredients_list', methods: ['GET'])]
    public function index(IngredientRepository $ingredientRepository): JsonResponse
    {
        $ingredients = $ingredientRepository->findAllIngredients();

        return $this->json($ingredients);
    }
}
