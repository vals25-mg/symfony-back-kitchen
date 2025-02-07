<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Commande
 *
 * @ORM\Table(name="commande", indexes={@ORM\Index(name="IDX_6EEAA67DAB18BE05", columns={"id_plat"}), @ORM\Index(name="IDX_6EEAA67DE173B1B8", columns={"id_client"})})
 * @ORM\Entity
 */
class Commande
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_commande", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="commande_id_commande_seq", allocationSize=1, initialValue=1)
     */
    private $idCommande;

    /**
     * @var int|null
     *
     * @ORM\Column(name="quantite_plat", type="integer", nullable=true)
     */
    private $quantitePlat;

    /**
     * @var int|null
     *
     * @ORM\Column(name="etat", type="integer", nullable=true)
     */
    private $etat = '0';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_heure_commande", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $dateHeureCommande = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_heure_livraison", type="datetime", nullable=true)
     */
    private $dateHeureLivraison;

    /**
     * @var \Plat
     *
     * @ORM\ManyToOne(targetEntity="Plat")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_plat", referencedColumnName="id_plat")
     * })
     */
    private $idPlat;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_client", referencedColumnName="id_utilisateur")
     * })
     */
    private $idClient;

    public function getIdCommande(): ?int
    {
        return $this->idCommande;
    }

    public function getQuantitePlat(): ?int
    {
        return $this->quantitePlat;
    }

    public function setQuantitePlat(?int $quantitePlat): static
    {
        $this->quantitePlat = $quantitePlat;

        return $this;
    }

    public function getEtat(): ?int
    {
        return $this->etat;
    }

    public function setEtat(?int $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getDateHeureCommande(): ?\DateTimeInterface
    {
        return $this->dateHeureCommande;
    }

    public function setDateHeureCommande(?\DateTimeInterface $dateHeureCommande): static
    {
        $this->dateHeureCommande = $dateHeureCommande;

        return $this;
    }

    public function getDateHeureLivraison(): ?\DateTimeInterface
    {
        return $this->dateHeureLivraison;
    }

    public function setDateHeureLivraison(?\DateTimeInterface $dateHeureLivraison): static
    {
        $this->dateHeureLivraison = $dateHeureLivraison;

        return $this;
    }

    public function getIdPlat(): ?Plat
    {
        return $this->idPlat;
    }

    public function setIdPlat(?Plat $idPlat): static
    {
        $this->idPlat = $idPlat;

        return $this;
    }

    public function getIdClient(): ?Utilisateur
    {
        return $this->idClient;
    }

    public function setIdClient(?Utilisateur $idClient): static
    {
        $this->idClient = $idClient;

        return $this;
    }


}
