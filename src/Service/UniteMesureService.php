<?php

namespace App\Service;

use App\Entity\UniteMesure;
use App\Repository\UniteMesureRepository;

class UniteMesureService
{
    private UniteMesureRepository $uniteMesureRepository;

    public function __construct(UniteMesureRepository $uniteMesureRepository)
    {
        $this->uniteMesureRepository = $uniteMesureRepository;
    }

    public function createUniteMesure(string $nomUnite): UniteMesure
    {
        $uniteMesure = new UniteMesure();
        $uniteMesure->setNomUnite($nomUnite);

        $this->uniteMesureRepository->save($uniteMesure);

        return $uniteMesure;
    }

    public function deleteUniteMesure(int $id): void
    {
        $uniteMesure= $this->uniteMesureRepository->find($id);

        if (!$uniteMesure) {
            throw new EntityNotFoundException('Unité de mesure non trouvée.');
        }
        $this->uniteMesureRepository->delete($uniteMesure);
    }

    public function updateUniteMesure(int $id, string $nomUnite): UniteMesure
    {
        $uniteMesure = $this->uniteMesureRepository->find($id);

        if (!$uniteMesure) {
            throw new EntityNotFoundException('Unité de mesure non trouvée.');
        }

        $uniteMesure->setNomUnite($nomUnite);
        $this->uniteMesureRepository->save($uniteMesure);

        return $uniteMesure;
    }
}
