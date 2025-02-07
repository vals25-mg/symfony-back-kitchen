<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Recette
 *
 * @ORM\Table(name="recette", indexes={@ORM\Index(name="IDX_49BB6390AB18BE05", columns={"id_plat"}), @ORM\Index(name="IDX_49BB6390CE25F8A7", columns={"id_ingredient"})})
 * @ORM\Entity
 */
class Recette
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_recette", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="recette_id_recette_seq", allocationSize=1, initialValue=1)
     */
    private $idRecette;

    /**
     * @var float|null
     *
     * @ORM\Column(name="quantite_ingredient", type="float", precision=10, scale=0, nullable=true)
     */
    private $quantiteIngredient;

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
     * @var \Ingredient
     *
     * @ORM\ManyToOne(targetEntity="Ingredient")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_ingredient", referencedColumnName="id_ingredient")
     * })
     */
    private $idIngredient;

    public function getIdRecette(): ?int
    {
        return $this->idRecette;
    }

    public function getQuantiteIngredient(): ?float
    {
        return $this->quantiteIngredient;
    }

    public function setQuantiteIngredient(?float $quantiteIngredient): static
    {
        $this->quantiteIngredient = $quantiteIngredient;

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
