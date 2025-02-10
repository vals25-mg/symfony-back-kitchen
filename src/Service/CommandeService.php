<?php

namespace App\Service;

use App\Entity\Commande;
use App\Entity\Plat;
use App\Entity\Utilisateur;
use App\Repository\CommandeRepository;
use App\Repository\PlatRepository;
use DateTimeImmutable;
use DateTimeZone;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CommandeService
{
    private CommandeRepository $commandeRepository;
    private PlatRepository $platRepository;
    private UtilisateurRepository $utilisateurRepository;
    private Security $security;
    private EntityManagerInterface $entityManager;



    public function __construct(
        CommandeRepository $commandeRepository,
        PlatRepository $platRepository,
        UtilisateurRepository $utilisateurRepository,
        Security $security,
        EntityManagerInterface $entityManager
    ) {
        $this->commandeRepository = $commandeRepository;
        $this->platRepository = $platRepository;
        $this->utilisateurRepository = $utilisateurRepository;
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    public function addCommande(int $idPlat, string $idClient, int $quantitePlat): Commande
    {

        $dateCommande = new \DateTimeImmutable('now', new \DateTimeZone('Africa/Nairobi'));

        $plat = $this->platRepository->find($idPlat);
        if (!$plat) {
            throw new \InvalidArgumentException('Plat introuvable.');
        }

        $client = $this->utilisateurRepository->find($idClient);
        if (!$client) {
            throw new \InvalidArgumentException('client introuvable.');
        }

        // $interval = new \DateInterval('PT' . preg_replace('/^(\d+):(\d+):(\d+)$/', '$1H$2M$3S', $plat->getTempsCuisson()));

        $commande = new Commande();
        $commande->setIdPlat($plat);
        $commande->setIdClient($client);
        $commande->setQuantitePlat($quantitePlat);
        $commande->setEtat(5);
        $commande->setDateHeureCommande($dateCommande);
        // $commande->setDateHeureLivraison($dateCommande->add($interval));

        $this->entityManager->persist($commande);
        $this->entityManager->flush();

        return $commande;

    }


    /*public function updateIngredient(int $id, ?string $nomIngredient, ?int $idUniteMesure, ?UploadedFile $imageFile, ?UploadedFile $logoFile): ?Ingredient
    {

        $ingredient = $this->ingredientRepository->find($id);
        if (!$ingredient) {
            throw new \InvalidArgumentException('Ingredient not found!');
        }

        if ($nomIngredient !== null) {
            $ingredient->setNomIngredient($nomIngredient);
        }

        if ($idUniteMesure !== null) {
            $uniteMesure = $this->uniteMesureRepository->find($idUniteMesure);
            if (!$uniteMesure) {
                throw new \InvalidArgumentException('Unité de mesure introuvable!');
            }
            $ingredient->setIdUniteMesure($uniteMesure);
        }

        $uploadDir = __DIR__ . '/../../assets/img/uploads/';

        if ($imageFile !== null) {
            $fileName = $imageFile->getClientOriginalName();
            $imageFile->move($uploadDir, $fileName);
            $ingredient->setUrl('/uploads/' . $fileName);
        }

        if ($logoFile !== null) {
            $logoFileName = $logoFile->getClientOriginalName();
            $logoFile->move($uploadDir, $logoFileName);
            $ingredient->setLogo('/uploads/' . $logoFileName);
        }

        $this->entityManager->persist($ingredient);
        $this->entityManager->flush();

        return $ingredient;
    }*/


}
