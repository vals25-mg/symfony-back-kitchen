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

    #[Route('/api/v1/add/commande', name: 'add_commande', methods: ['POST'])]
    public function addCommande(Request $request, SerializerInterface $serializer): JsonResponse
    {
        // Get the Authorization header from the request
        $token = $request->headers->get('Authorization');

        // Check if token is provided and has the correct format
        if (!$token || !str_starts_with($token, 'Bearer ')) {
            return $this->json(['error' => 'Token introuvable ou invalide!'], 401);
        }

        $firebaseToken = str_replace('Bearer ', '', $token);
        $idUtilisateur = "-";

        try {
            // Remove 'Bearer ' and verify Firebase Token
            $firebaseUserId = $this->firebaseService->verifyIdToken($firebaseToken);
            $idUtilisateur = $firebaseUserId['user_id'] ?? 'inconnu';
            // var_dump($firebaseUserId);
            // Check if Firebase User ID is valid
            if (!$firebaseUserId) {
                throw new \InvalidArgumentException('Utilisateur non trouvé dans le token.');
            }
        } catch (\Exception $e) {
            return $this->json(['error' => 'Utilisateur non connecté: ' . $e->getMessage()], 401);
        }

        // Decode JSON body correctly
        // echo "mety";
        // echo $idUtilisateur;
        $data = json_decode($request->getContent(), true);
        // echo json_encode($data);
        // Check if data is an array of orders
        if (!is_array($data)) {
            return $this->json(['error' => 'Les données doivent être un tableau de commandes!'], 400);
        }

        // Initialize an array to store inserted orders
        $insertedOrders = [];

        foreach ($data as $orderData) {
            $idPlat = $orderData['id'] ?? null;
            $quantite = $orderData['quantity'] ?? null;

            // Check if all required fields are present for each order
            if (!$idPlat || !$quantite) {
                return $this->json(['error' => 'Tous les champs sont requis pour chaque commande!'], 400);
            }

            try {
                // Add each commande and pass Firebase UID
                $commande = $this->commandeService->addCommande($idPlat, $idUtilisateur, $quantite);

                // Store response data for each successfully added commande
                $insertedOrders[] = [
                    'id' => $commande->getIdCommande(),
                    'date_debut_commande' => $commande->getDateHeureCommande()->format('Y-m-d H:i:s'),
                ];

            } catch (\Exception $e) {
                // Log or return error for the specific order, but continue processing other orders
                $insertedOrders[] = [
                    'error' => 'Erreur lors de l\'ajout de la commande: ' . $e->getMessage(),
                    'order_data' => $orderData
                ];
            }
        }

        // Return all inserted orders or errors
        return new JsonResponse(['inserted_orders' => $insertedOrders], 200);
    }



    #[Route('/api/v1/update/commande/{id}', name: 'update_commande', methods: ['POST', 'PUT', 'PATCH'])]
    public function updateCommande(Request $request, $id, SerializerInterface $serializer): JsonResponse
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
            $decodedToken = $this->firebaseService->verifyTokenId($firebaseToken);

            // Extract the Firebase user ID (uid) from the decoded token
            $firebaseUserId = $decodedToken['uid'];

            // If the UID is not found, throw an error
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

        // Check if the Commande exists
        $commande = $this->commandeRepository->find($id); // Ensure the id is being passed correctly in the URL
        if (!$commande) {
            return $this->json(['error' => 'Commande introuvable!'], 404);
        }

        // If idPlat is provided, update it
        if ($idPlat) {
            $plat = $this->platRepository->find($idPlat); // Assuming you have a Plat repository to get Plat by id
            if (!$plat) {
                return $this->json(['error' => 'Plat introuvable!'], 400);
            }

            // Get the current interval from Plat
            $currentInterval = $plat->getInterval();

            // If quantite >= 2, multiply the interval by quantite
            $newInterval = ($quantite >= 2) ? $currentInterval * $quantite : $currentInterval;

            // Set the new interval in Commande
            $commande->setInterval($newInterval); // Ensure this method is available in your Commande entity
        }

        // If quantite is provided, update it
        if ($quantite !== null) {
            $commande->setQuantite($quantite); // Assuming you have a setter for quantite
        }

        // Persist the updated Commande entity
        $this->entityManager->flush();

        // Return response with updated values
        return $this->json([
            'id' => $commande->getIdCommande(),
            'date_debut_commande' => $commande->getDateHeureCommande()->format('Y-m-d H:i:s'),
            'date_fin_commande' => $commande->getDateHeureLivraison()->format('Y-m-d H:i:s'),
            'interval' => $commande->getInterval(),
            // Return updated interval
            'quantite' => $commande->getQuantite() // Return updated quantite if changed
        ], 200);
    }



    #[Route('/api/v1/get_list_commandes', name: 'get_commandes', methods: ['GET'])]
    public function listCommandes(): JsonResponse
    {
        $commandes = $this->commandeRepository->findAllCommande();

        return $this->json($commandes);
    }

    #[Route('/api/v1/get_list_commandes_payes', name: 'get_commandes', methods: ['GET'])]
    public function listCommandes_payes(): JsonResponse
    {
        $commandes = $this->commandeRepository->findCommandeByEtat5();

        return $this->json($commandes);
    }
}
