<?php

namespace App\Service;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Symfony\Component\Security\Core\Exception\UserAlreadyExistsException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UtilisateurService
{
    private UtilisateurRepository $utilisateurRepository;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UtilisateurRepository $utilisateurRepository, UserPasswordHasherInterface $passwordHasher)
    {
        $this->utilisateurRepository = $utilisateurRepository;
        $this->passwordHasher = $passwordHasher;
    }

    public function inscrire(string $email, string $password): Utilisateur
    {
        // Vérifie si l'email existe déjà
        if ($this->utilisateurRepository->findByEmail($email)) {
            throw new UserAlreadyExistsException('Cet email est déjà utilisé.');
        }

        $utilisateur = new Utilisateur();
        $utilisateur->setIdUtilisateur("UYHGftfytydyrdyy");
        $utilisateur->setEmail($email);

        // Hash du mot de passe
        // $hashedPassword = $this->passwordHasher->hashPassword($utilisateur, $password);
        $utilisateur->setPassword($password);

        $this->utilisateurRepository->save($utilisateur);

        return $utilisateur;
    }
}
