<?php

namespace App\Service;

use App\Entity\StockIngredient;
use App\Entity\Ingredient;
use App\Repository\StockIngredientRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use \DateTime;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class StockIngredientService
{
    private StockIngredientRepository $stockIngredientRepository;
    private IngredientRepository $ingredientRepository;
    private Security $security;
    private EntityManagerInterface $entityManager;

    public function __construct(StockIngredientRepository $stockIngredientRepository, IngredientRepository $ingredientRepository, Security $security, EntityManagerInterface $entityManager)
    {
        $this->stockIngredientRepository = $stockIngredientRepository;
        $this->ingredientRepository = $ingredientRepository;
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    public function addStockIngredient(int $idIngredient, float $valeurEntree): StockIngredient
    {

        $ingredient = $this->ingredientRepository->find($idIngredient);
        if (!$ingredient) {
            throw new \InvalidArgumentException('Ingredient introuvable.');
        }
        
        $stockIngredient = new StockIngredient();
        $stockIngredient->setIngredient($ingredient);
        $stockIngredient->setValeurEntree($valeurEntree);
        $stockIngredient->setValeurSortie(0);

        $dateMouvement = new \DateTime('now', new \DateTimeZone('Africa/Nairobi'));

        $stockIngredient->setDateMouvement($dateMouvement);

        $this->entityManager->persist($stockIngredient);
        $this->entityManager->flush();

        return $stockIngredient;

    }

    public function updateStockIngredient(int $id, array $data): StockIngredient
    {
        $stockIngredient = $this->stockIngredientRepository->find($id);

        if (!$stockIngredient) {
            throw new \InvalidArgumentException('StockIngredient introuvable.');
        }

        if (isset($data['id_ingredient'])) {
            $ingredient = $this->ingredientRepository->find($data['id_ingredient']);
            if (!$ingredient) {
                throw new \InvalidArgumentException('Ingredient introuvable.');
            }
            $stockIngredient->setIngredient($ingredient);
        }

        // Update valeurEntree if provided, otherwise keep existing value
        if (isset($data['valeur_entree'])) {
            $stockIngredient->setValeurEntree($data['valeur_entree']);
        }

        // Update valeurSortie if provided, otherwise keep existing value
        if (isset($data['valeur_sortie'])) {
            $stockIngredient->setValeurSortie($data['valeur_sortie']);
        }

        $this->entityManager->flush();

        $this->entityManager->refresh($stockIngredient);

        return $stockIngredient;
    }
    

}
