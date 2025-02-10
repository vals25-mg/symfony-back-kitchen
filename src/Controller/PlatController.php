<?php

namespace App\Controller;

use App\Service\PlatService;
use App\Repository\PlatRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\FirebaseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class PlatController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private PlatService $platService;
    private PlatRepository $platRepository;
    private FirebaseService $firebaseService;


    public function __construct(PlatService $platService, PlatRepository $platRepository, EntityManagerInterface $entityManager, FirebaseService $firebaseService)
    {
        $this->platService = $platService;
        $this->platRepository = $platRepository;
        $this->entityManager = $entityManager;
        $this->firebaseService = $firebaseService;
    }

    #[Route('/api/v1/add/plat', name: 'add_plat', methods: ['POST'])]
    public function addPlat(Request $request, SerializerInterface $serializer): JsonResponse
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

        $nomPlat = $request->request->get('nom_plat');
        $imageFile = $request->files->get('url');
        $logo = $request->files->get('logo');
        $tempsCuisson = $request->request->get('temps_cuisson');
        $prix = $request->request->get('temps_cuisson');

        if (!$logo) {
            return $this->json(['error' => 'Logo file est null!'], 400);
        }

        if (!$nomPlat || !$imageFile || !$logo || !$tempsCuisson || !$prix) {
            return $this->json(['error' => 'Tout les champs sont requis!'], 400);
        }

        try {

            $plat = $this->platService->addPlat($nomPlat, $imageFile, $logo, $tempsCuisson, $prix);
            $jsonContent = $serializer->serialize($plat, 'json', ['groups' => ['plat:read']]);
            return new JsonResponse([
                'id' => $plat->getIdPlat(),
                'nom_plat' => $plat->getNomPlat(),
                'imgUrl' => $plat->getUrl(),
                'logo' => $plat->getLogo(),
                'temps_cuisson' => $plat->getTempsCuisson(),
                'prix' => $plat->getPrix(),
            ], 200);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }


    #[Route('/api/v1/update_plat/{id}', name: 'update_plat', methods: ['POST', 'PUT', 'PATCH'])]
    public function updatePlat(Request $request, int $id): JsonResponse
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

        $nomPlat = $request->request->get('nom_plat');
        $imageFile = $request->files->get('url');
        $logoFile = $request->files->get('logo');
        $tempsCuisson = $request->request->get('temps_cuisson');
        $prix = $request->request->get('prix');

        if (!$nomPlat && !$imageFile && !$logoFile && !$tempsCuisson && !$prix) {
            return $this->json(['error' => 'Aucun champ fourni pour la mise à jour.'], 400);
        }

        try {
            $plat = $this->platService->updatePlat($id, $nomPlat, $imageFile, $logoFile, $tempsCuisson, $prix);
            return $this->json([
                'id' => $plat->getIdPlat(),
                'nomPlat' => $plat->getNomPlat(),
                'imgUrl' => $plat->getUrl(),
                'logo' => $plat->getLogo(),
                'temps_Cuisson' => $plat->getTempsCuisson(),
                'prix' => $plat->getPrix(),
            ], 200, [], ['groups' => 'ingredient:read']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }


    #[Route('api/v1/get_list_plat', name: 'get_list_plat', methods: ['GET'])]
    public function listPlat(): JsonResponse
    {
        
        $plats = $this->platRepository->findAllPlat();

        $data = [];
        foreach ($plats as $plat) {
            $data[] = [
                'id' => $plat->getIdPlat(),
                'nom_plat' => $plat->getNomPlat(),
                'img_url' => $plat->getUrl(),
                'logo' => $plat->getLogo(),
                'temps_cuisson' => $plat->getTempsCuisson(),
                'prix' => $plat->getPrix()
            ];
        }

        return new JsonResponse($data);
    }
}
