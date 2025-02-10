<?php

namespace App\Repository;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    public function save(Commande $commande): void
    {
        $this->_em->persist($commande);
        $this->_em->flush();
    }

    public function findCommandeById(int $id): ?Commande
    {
        return $this->find($id);
    }

    public function findAllCommande(): array
    {
        return $this->findAll();
    }
    public function findCommandeByEtat5(): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.etat = :etat')
            ->setParameter('etat', 5)
            ->getQuery()
            ->getResult();
    }
}
