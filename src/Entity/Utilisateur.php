<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Utilisateur
 *
 * @ORM\Table(name="utilisateur")
 * @ORM\Entity
 */
class Utilisateur
{
    /**
     * @var string
     *
     * @ORM\Column(name="id_utilisateur", type="string", length=200, nullable=false)
     * @ORM\Id
     */
    //  * @ORM\GeneratedValue(strategy="SEQUENCE")
    //  * @ORM\SequenceGenerator(sequenceName="utilisateur_id_utilisateur_seq", allocationSize=1, initialValue=1)
    private $idUtilisateur;

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=50, nullable=true)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="password", type="string", length=200, nullable=true)
     */
    private $password;

    /**
     * @var array|null
     *
     * @ORM\Column(name="role", type="json", length=15, nullable=true)
     */
    private $role= [];

    public function __construct()
    {
        //$this->roles[] = 'ROLE_USER';
    }

    public function getId(): ?string
    {
        return $this->idUtilisateur;
    }

    public function setId(string $id): self
    {
        $this->idUtilisateur = $id;
        return $this;
    }

    public function getIdUtilisateur(): ?string
    {
        return $this->idUtilisateur;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): ?array
    {
        return $this->role;
    }

    public function setRole(?array $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->role;
    }

    public function setRoles(array $roles): self
    {
        $this->role = $roles;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

}
