<?php

namespace App\Controller;

use App\Service\UtilisateurService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\UserAlreadyExistsException;

class UtilisateurController extends AbstractController
{
    private UtilisateurService $utilisateurService;

    public function __construct(UtilisateurService $utilisateurService)
    {
        $this->utilisateurService = $utilisateurService;
    }

    #[Route('/api/inscription', name: 'inscription_utilisateur', methods: ['POST'])]
    public function inscrire(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['email']) || empty($data['password'])) {
            return new JsonResponse(['error' => 'Email et mot de passe sont requis.'], 400);
        }

        try {
            $utilisateur = $this->utilisateurService->inscrire($data['email'], $data['password']);
            return new JsonResponse([
                'id' => $utilisateur->getIdUtilisateur(),
                'email' => $utilisateur->getEmail(),
                'message' => 'Inscription réussie !'
            ], 201);
        } catch (UserAlreadyExistsException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 409);
        }
    }
}
