<?php

namespace App\Repository;

use App\Entity\UniteMesure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UniteMesureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UniteMesure::class);
    }

    public function save(UniteMesure $uniteMesure): void
    {
        $this->_em->persist($uniteMesure);
        $this->_em->flush();
    }

    public function delete(UniteMesure $uniteMesure): void
    {
        $this->_em->remove($uniteMesure);
        $this->_em->flush();
    }

    public function findUniteMesureById(int $id): ?UniteMesure
    {
        return $this->find($id);
    }

    public function findAllUniteMesures(): array
    {
        return $this->findAll();
    }
}
