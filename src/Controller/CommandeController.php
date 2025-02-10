<?php

namespace App\Controller;

use App\Service\CommandeService;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\FirebaseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class CommandeController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private CommandeService $commandeService;
    private CommandeRepository $commandeRepository;
    private FirebaseService $firebaseService;


    public function __construct(CommandeService $commandeService, CommandeRepository $commandeRepository, EntityManagerInterface $entityManager, FirebaseService $firebaseService)
    {
        $this->commandeService = $commandeService;
        $this->commandeRepository = $commandeRepository;
        $this->entityManager = $entityManager;
        $this->firebaseService = $firebaseService;
    }

    /*#[Route('/api/v1/add/commande', name: 'add_commande', methods: ['POST'])]
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

        $idPlat = $request->request->get('id_plat');
        $idClient = $request->files->get('id_client');
        $quantite = $request->files->get('quantite_plat');

        if (!$idPlat || !$idClient || !$quantite) {
            return $this->json(['error' => 'Tout les champs sont requis!'], 400);
        }

        try {

            $plat = $this->commandeService->addCommande($idPlat, $idClient, $quantite);
            $jsonContent = $serializer->serialize($plat, 'json', ['groups' => ['plat:read']]);
            return new JsonResponse([
                'id' => $plat->getIdCommande(),
                'date_debut_commande' => $plat->getDateHeureCommande(),
                'date_fin_commande' => $plat->getDateHeureLivraison(),
            ], 200);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }*/

    #[Route('/api/v1/add/commande', name: 'add_commande', methods: ['POST'])]
    public function addCommande(Request $request, SerializerInterface $serializer): JsonResponse
    {
        // Get the Authorization header from the request
        $token = $request->headers->get('Authorization');
    
        // Check if token is provided and has the correct format
        if (!$token || !str_starts_with($token, 'Bearer ')) {
            return $this->json(['error' => 'Token introuvable ou invalide!'], 401);
        }
    
        try {
            // Remove 'Bearer ' and verify Firebase Token
            $firebaseToken = str_replace('Bearer ', '', $token);
            $firebaseUserId = $this->firebaseService->verifyTokenId($firebaseToken);
    
            // Check if Firebase User ID is valid
            if (!$firebaseUserId) {
                throw new \InvalidArgumentException('Utilisateur non trouvé dans le token.');
            }
        } catch (\Exception $e) {
            return $this->json(['error' => 'Utilisateur non connecté: ' . $e->getMessage()], 401);
        }
    
        // Decode JSON body correctly
        $data = json_decode($request->getContent(), true);
        $idPlat = $data['id_plat'] ?? null;
        $quantite = $data['quantite_plat'] ?? null;
    
        // Check if all required fields are present
        if (!$idPlat || !$quantite) {
            return $this->json(['error' => 'Tous les champs sont requis!'], 400);
        }
    
        try {
            // Add commande and pass Firebase UID
            $commande = $this->commandeService->addCommande($idPlat, $firebaseUserId, $quantite);
    
            // Return response with the newly created commande data
            return new JsonResponse([
                'id' => $commande->getIdCommande(),
                'date_debut_commande' => $commande->getDateHeureCommande()->format('Y-m-d H:i:s'),
                'date_fin_commande' => $commande->getDateHeureLivraison()->format('Y-m-d H:i:s'),
            ], 200);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }
    
    
    
    

    /*#[Route('/api/v1/add/commande', name: 'add_commande', methods: ['POST'])]
    public function addCommande(Request $request, SerializerInterface $serializer): JsonResponse
    {
        $token = $request->headers->get('Authorization');
    
        // Check if token is provided and has the correct format
        if (!$token || !str_starts_with($token, 'Bearer ')) {
            return $this->json(['error' => 'Token introuvable ou invalide!'], 401);
        }
    
        try {
            // Remove 'Bearer ' and verify Firebase Token
            $firebaseToken = str_replace('Bearer ', '', $token);
            $firebaseUserId = $this->firebaseService->verifyTokenById($firebaseToken);
    
            // Log the UID for debugging
            error_log('Firebase User UID: ' . $firebaseUserId);
    
            if (!$firebaseUserId) {
                throw new \InvalidArgumentException('Utilisateur non trouvé dans le token.');
            }
        } catch (\Exception $e) {
            return $this->json(['error' => 'Utilisateur non connecté: ' . $e->getMessage()], 401);
        }
    
        // Decode JSON body correctly
        $data = json_decode($request->getContent(), true);
        $idPlat = $data['id_plat'] ?? null;
        $quantite = $data['quantite_plat'] ?? null;
    
        // Check if all required fields are present
        if (!$idPlat || !$quantite) {
            return $this->json(['error' => 'Tous les champs sont requis!'], 400);
        }
    
        try {
            // Add commande and pass Firebase UID
            $commande = $this->commandeService->addCommande($idPlat, $firebaseUserId, $quantite);
    
            // Return response with the newly created commande data
            return new JsonResponse([
                'id' => $commande->getIdCommande(),
                'date_debut_commande' => $commande->getDateHeureCommande()->format('Y-m-d H:i:s'),
                'date_fin_commande' => $commande->getDateHeureLivraison()->format('Y-m-d H:i:s'),
            ], 200);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }*/


    /*#[Route('/api/v1/update_plat/{id}', name: 'update_plat', methods: ['POST', 'PUT', 'PATCH'])]
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
    }*/


    #[Route('api/v1/get_list_commandes', name: 'get_commandes', methods: ['GET'])]
    public function listCommandes(): JsonResponse
    {
        $commandes = $this->commandeRepository->findAllCommande();

        return $this->json($commandes);
    }
}
