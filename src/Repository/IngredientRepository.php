<?php


namespace App\Repository;

use App\Entity\Ingredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ingredient>
 */
class IngredientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ingredient::class);
    }

    /**
     * Récupérer tous les ingrédients
     */
    public function findAllIngredients(): array
    {
        return $this->createQueryBuilder('i')
            ->orderBy('i.idIngredient', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function save(Ingredient $ingredient): void
    {
        $this->_em->persist($ingredient);
        $this->_em->flush();
    }

    public function findIngredientById(int $id): ?Ingredient
    {
        return $this->find($id);
    }
}
