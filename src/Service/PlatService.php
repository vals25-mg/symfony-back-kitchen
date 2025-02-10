<?php

namespace App\Service;

use App\Entity\Plat;
use App\Repository\PlatRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Repository\UniteMesureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class PlatService
{
    private PlatRepository $platRepository;
    private Security $security;
    private EntityManagerInterface $entityManager;

    public function __construct(PlatRepository $platRepository, Security $security, EntityManagerInterface $entityManager)
    {
        $this->platRepository = $platRepository;
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    public function addPlat(string $nomPlat, UploadedFile $imgUrl, UploadedFile $logo, string $tempsCuisson, float $prix): Plat
    {

        $uploadDir = __DIR__ . '/../../assets/img/uploads/';
        $fileName = $imgUrl->getClientOriginalName(); 
        $filePath = $uploadDir . '/' . $fileName;

        if (file_exists($filePath)) {
            unlink($filePath); 
        }

        $imgUrl->move($uploadDir, $fileName);
        $imgUrlPath = '/uploads/' . $fileName;

        $logoFileName = $logo->getClientOriginalName(); 
        $logoFilePath = $uploadDir . '/' . $logoFileName;

        if (file_exists($logoFilePath)) {
            unlink($logoFilePath); 
        }

        $logo->move($uploadDir, $logoFileName);
        $logoPath = '/uploads/' . $logoFileName;
        
        $plat = new Plat();
        $plat->setNomPlat($nomPlat);
        $plat->setUrl($imgUrlPath);
        $plat->setLogo($logoPath);
        $plat->setTempsCuisson($tempsCuisson);
        $plat->setPrix($prix);

        $this->entityManager->persist($plat);
        $this->entityManager->flush();

        return $plat;

    }

    public function updatePlat(int $id, ?string $nomPlat, ?UploadedFile $imageFile, ?UploadedFile $logoFile, ?string $tempsCuisson, ?float $prix): ?Plat
    {

        $plat = $this->platRepository->find($id);
        if (!$plat) {
            throw new \InvalidArgumentException('Plat introuvable!');
        }

        if ($nomPlat !== null) {
            $plat->setNomPlat($nomPlat);
        }

        $uploadDir = __DIR__ . '/../../assets/img/uploads/';

        if ($imageFile !== null) {
            $fileName = $imageFile->getClientOriginalName(); 
            $imageFile->move($uploadDir, $fileName);
            $plat->setUrl('/uploads/' . $fileName);
        }

        if ($logoFile !== null) {
            $logoFileName = $logoFile->getClientOriginalName(); 
            $logoFile->move($uploadDir, $logoFileName);
            $plat->setLogo('/uploads/' . $logoFileName);
        }

        if ($tempsCuisson !== null) {
            $plat->setTempsCuisson($tempsCuisson);
        }

        if ($prix !== null) {
            $plat->setPrix($prix);
        }

        $this->entityManager->persist($plat);
        $this->entityManager->flush();

        return $plat;
    }
    

}
