<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * StockIngredient
 *
 * @ORM\Table(name="stock_ingredient", indexes={@ORM\Index(name="IDX_C5E68FDCCE25F8A7", columns={"id_ingredient"})})
 * @ORM\Entity
 */
class StockIngredient
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_stock_ingredient", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="stock_ingredient_id_stock_ingredient_seq", allocationSize=1, initialValue=1)
     */
    private $idStockIngredient;

    /**
     * @var float|null
     *
     * @ORM\Column(name="entree", type="float", precision=10, scale=0, nullable=true)
     */
    private $entree = '0';

    /**
     * @var float|null
     *
     * @ORM\Column(name="sortie", type="float", precision=10, scale=0, nullable=true)
     */
    private $sortie = '0';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_mouvement", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $dateMouvement = 'CURRENT_TIMESTAMP';

    /**
     * @var \Ingredient
     *
     * @ORM\ManyToOne(targetEntity="Ingredient")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_ingredient", referencedColumnName="id_ingredient")
     * })
     */
    private $idIngredient;

    public function getIdStockIngredient(): ?int
    {
        return $this->idStockIngredient;
    }

    public function getEntree(): ?float
    {
        return $this->entree;
    }

    public function setEntree(?float $entree): static
    {
        $this->entree = $entree;

        return $this;
    }

    public function getSortie(): ?float
    {
        return $this->sortie;
    }

    public function setSortie(?float $sortie): static
    {
        $this->sortie = $sortie;

        return $this;
    }

    public function getDateMouvement(): ?\DateTimeInterface
    {
        return $this->dateMouvement;
    }

    public function setDateMouvement(?\DateTimeInterface $dateMouvement): static
    {
        $this->dateMouvement = $dateMouvement;

        return $this;
    }

    public function getIdIngredient(): ?Ingredient
    {
        return $this->idIngredient;
    }

    public function setIdIngredient(?Ingredient $idIngredient): static
    {
        $this->idIngredient = $idIngredient;

        return $this;
    }


}
